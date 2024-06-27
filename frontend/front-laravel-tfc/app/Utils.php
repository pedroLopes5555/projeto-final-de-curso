<?php
namespace App;

use Illuminate\Mail\Markdown;
use Illuminate\View\Compilers\BladeCompiler;

use App\Models\ArticleCategory;
use App\Models\ContentCategory;
use App\Models\Page;
use App\Exceptions\SlugException;

use App\Models\Media;
use App\Models\Document;

use DB;

use Illuminate\Support\Facades\Route;
class Utils{


	/*
    $lang = false | null
      Use current language

    $lang = true
      Use other language (current pt -> en) (current en -> pt)

    $lang = "pt" | "en"
      Use that language

    $key = null
      Use current route
  */
  public static function buildUrl($lang, $bindings = [], $key = null){
    // Get from and to langs
    $fromLang = \Lang::getLocale();
    if($lang === null || $lang === false) $lang = $fromLang;
    else if($lang === true) $lang = $fromLang == 'en' ? 'pt' : 'en';

    // Get routes
    $routes = Route::getRoutes();
    if($key == null){
      // Get current route and name
      $currRoute = Route::getCurrentRoute();
      $key = $currRoute->getName();

      // Get the "pure" key of the route (without the lang prefix)
      $pureKey = explode('.', $key)[1] ?? null;
      if($pureKey == null) return $currRoute->uri;
    }else{
      $pureKey = $key;
    }

    // Get the route for the target lang
    $otherRoute = $routes->getByName($lang.'.'.$pureKey);
    if($otherRoute == null) return self::langBack($lang);

    // Get the url of the target route
    $prefix = '/';
    if($lang == 'en' && in_array('en', $otherRoute->action['middleware'])){
      $prefix = '/en/';
    }
    $url = $otherRoute->uri;
    if($url != '/') $url = $prefix.$url;

    // Allow easy lang bindings choice
    // Replace keys with keys ending in _en if language is EN
    // Can be sent separately as either PT or EN or both with _en suffix for EN
    if($lang == 'en'){
      foreach($bindings as $key => $bind){
        if(!str_ends_with($key, '_en')) continue;
        // if(!self::endsWith($key, '_en')) continue;
        $bindings[substr($key, 0, -3)] = $bind;
      }
    }

    // Replace bindings. On fail return to homepage
    $fail = false;
    $url = preg_replace_callback(
      '/\{.+?\}/',
      // For each route token replace it with the binding of the same name
      function ($matches) use ($bindings, &$fail){
        $m = trim($matches[0], '{}');
        if(!isset($bindings[$m])) return $fail = true;
        return $bindings[$m];
      },
      $url
    );
    // if($fail) return self::langBack($lang);

    return $url;
  }

  private static $_count = 0;
  public static function count($n){
    return $n==(self::$_count++);
  }

  public static function checkSlugRoute($pt, $en){
    $routeList = \Route::getRoutes();

    foreach ($routeList as $route){
      if($route->action['prefix'] != '__front') continue;
      $uri = substr($route->uri, 8);
      $uri = explode('/', $uri, 2);
      $uri = $uri[0];
      if($uri == $pt)
        throw new SlugException("Duplicate slug '$pt' in 'laravel routes'.");
      if($uri == $en)
        throw new SlugException("Duplicate slug '$en' in 'laravel routes'.");
    }
  }
  public static function checkSlugArticleCategory($pt, $en, $except=null){
    $cat = ArticleCategory::where(function($query) use ($pt, $en){
        $query->where('article_category_slug', $pt)
          ->orWhere('article_category_slug_en', $en);
      })
      // ->where('visible', 1)
      ->where('article_category_id', '<>', $except)
      ->first();
    if($cat == null) return;

    $table = $cat->getTable();
    if($cat->article_category_slug == $pt)
      throw new SlugException("Duplicate slug '$pt' in '$table'.");
    if($cat->article_category_slug_en == $en)
      throw new SlugException("Duplicate slug '$en' in '$table'.");
  }
  public static function checkSlugContentCategory($pt, $en, $except=null){
    $cat = ContentCategory::where(function($query) use ($pt, $en){
        $query->where('content_category_slug', $pt)
          ->orWhere('content_category_slug_en', $en);
      })
      ->whereNull('content_category_parent_id')
      ->where('content_category_id', '<>', $except)
      ->first();
    if($cat == null) return;

    $table = $cat->getTable();
    if($cat->content_category_slug == $pt)
      throw new SlugException("Duplicate slug '$pt' in '$table'.");
    if($cat->content_category_slug_en == $en)
      throw new SlugException("Duplicate slug '$en' in '$table'.");
  }
  public static function checkSlugPage($pt, $en, $except=null){
    $cat = Page::where(function($query) use ($pt, $en){
        $query->where('page_slug', $pt)
          ->orWhere('page_slug_en', $en);
      })
      ->where('page_id', '<>', $except)
      ->first();
    if($cat == null) return;

    $table = $cat->getTable();
    if($cat->page_slug == $pt)
      throw new SlugException("Duplicate slug '$pt' in '$table'.");
    if($cat->page_slug_en == $en)
      throw new SlugException("Duplicate slug '$en' in '$table'.");
  }
  public static function checkSlugs($pt, $en, $except=[]){
    self::checkSlugRoute($pt, $en);
    self::checkSlugArticleCategory($pt, $en, $except['article']??null);
    self::checkSlugContentCategory($pt, $en, $except['content']??null);
    self::checkSlugPage($pt, $en, $except['page']??null);
  }

  public static function checkDocumentSlugs($pt, $en, $except=null){
    $doc = Document::where(function($query) use ($pt, $en){
        $query->where('document_slug', $pt)
          ->orWhere('document_slug_en', $en);
      })
      ->where('document_snowflake', '<>', $except)
      ->first();
    if($doc == null) return;

    $table = $doc->getTable();
    if($doc->document_slug == $pt)
      throw new SlugException("Duplicate slug '$pt' in '$table'.");
    if($doc->document_slug_en == $en)
      throw new SlugException("Duplicate slug '$en' in '$table'.");
  }
  public static function checkMediaSlugs($pt, $en, $except=null){
    $media = Media::where(function($query) use ($pt, $en){
        $query->where('media_slug', $pt)
          ->orWhere('media_slug', $en);
      })
      ->where('media_snowflake', '<>', $except)
      ->first();
    if($media == null) return;

    $table = $media->getTable();
    if($media->media_slug == $pt)
      throw new SlugException("Duplicate slug '$pt' in '$table'.");
    if($media->media_slug == $en)
      throw new SlugException("Duplicate slug '$en' in '$table'.");
  }
  public static function checkFileSlugs($pt, $en, $except=[]){
    self::checkMediaSlugs($pt, $en, $except['media']??null);
    self::checkDocumentSlugs($pt, $en, $except['document']??null);
  }

  public static function toSlug($name){
    setlocale(LC_CTYPE, 'pt_PT.UTF-8');
    return preg_replace(
      '/\-{2,}/',
      '-',
      preg_replace(
        '/\s+/',
        '-',
        preg_replace(
          '/[^a-z0-9 -]/',
          '',
          strtolower(
            iconv(
              'UTF-8',
              'ASCII//TRANSLIT//IGNORE',
              $name
            )
          )
        )
      )
    );
  }
  public static function toSlugID($name, $id, $length=256){
    $slug = Utils::toSlug($name);
    $id = '-'.$id;
    $length = $length - strlen($id);
    return substr($slug, 0, $length).$id;
  }

  public static function toCamelCase($str){
    $str = str_replace('_', '', ucwords($str, '_'));
    $str = strtolower($str[0]) . substr($str, 1);
    return $str;
  }
  const AWARE_WORDS = [
    'e', 'o', 'os', 'a', 'as',
    'de', 'do', 'dos', 'da', 'das'
  ];
  public static function awareCapitalize($str){
    $cap = mb_convert_case($str ?? '', MB_CASE_TITLE, 'UTF-8');
    $explode = explode(' ', $cap);
    foreach($explode as $index => $ex){
      $x = strtolower($ex);
      if(!in_array($x, self::AWARE_WORDS)) continue;
      $explode[$index] = $x;
    }
    return implode(' ', $explode);
  }

  public static function requestOr($pt, $en){
    return strtolower(request()->lang) == 'en' ? $en : $pt;
  }

  /* View */
  public static function renderBladeString($__str, $__vars=[]){
    // $compiler = new BladeCompiler();
    $__php = \Blade::compileString($__str);
    return Utils::renderPHPString($__php, $__vars);
  }
  public static function renderPHPString($__str, $__vars=[]){
    $__vars['__env'] = app(\Illuminate\View\Factory::class);
    extract($__vars);
    ob_start();
    try{
      eval('?>'.$__str);
    }catch(\Throwable $th){
      // $result = "";
      ob_end_clean();
      throw $th;
    }
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
  }

  /* Image */
  // http://image.intervention.io/api/fit
  public static function constrainImage($file, $width, $height, $position){
    $filename = $file->getClientOriginalName();

    $image = Image::make($file->getRealPath());
    $image = $image->orientate()->fit($width, $height, function ($constraint) {
      $constraint->upsize();
    }, $position);
    return $image;
  }
  public static function constrainAndSaveImage($file, $width, $height, $position, $filename){
    $dir = dirname($filename);
    Storage::disk('public')->makeDirectory($dir);

    $image = self::constrainImage($file, $width, $height, $position);
    $image->save(Storage::disk('public')->path($filename));
  }

  // Files
  public static function genFilename($prefix, $name, $ext, $suffix=null){
    $now = date('Ymdhis');

    if($suffix) $suffix = '_'.$suffix;
    return $prefix.'/'.$now.'_'.$name.$suffix.'.'.$ext;
  }
  public static function storeFile($file, $path){
    $info = pathinfo('/public'.$path);
    $path = $info['dirname'];
    $name = $info['basename'];
    $file->storeAs($path, $name);
  }
  public static function getDirContents($root, $dir = "", &$results = array()){
    $files = $dir ? scandir($root . DIRECTORY_SEPARATOR . $dir) : scandir($root);
    foreach ($files as $key => $value){
      if($value == "." || $value == "..") continue;
      $path = $dir ? $dir . DIRECTORY_SEPARATOR . $value : $value;
      $full_path = realpath($root . DIRECTORY_SEPARATOR . $path);
      if(!is_dir($full_path)) {
        $results[] = substr($path, 0, -5);
      }else{
        self::getDirContents($root, $path, $results);
      }
    }
    return $results;
  }

  // DB
  public static function accentParts($query, $q, $callback){
    // setlocale(LC_CTYPE, 'pt_PT.UTF-8');
    // $no_accents = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $q);
    $parts = preg_split("/\\s+/", $q);
    foreach($parts as $q){
      $q = strtolower("%$q%");
      $query = $query->where(function($query) use ($callback, $q){
        $callback($query, $q);
      });
    }
  }
  public static function DBWord($name){
    return DB::Raw("LOWER($name)");
  }
  public static function _accentParts($query, $q, $callback){
    setlocale(LC_CTYPE, 'pt_PT.UTF-8');
    $no_accents = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $q);
    $parts = preg_split("/\\s+/", $no_accents);
    foreach($parts as $q){
      $q = strtolower("%$q%");
      $query = $query->where(function($query) use ($callback, $q){
        $callback($query, $q);
      });
    }
  }
  public static function _DBWord($name){
    return DB::Raw("unaccent(LOWER($name))");
  }

  public static function setRelationItems($array){
    $array['class']::where($array['local_id_name'], $array['local_id'])->delete();
    $__insert = [];
    foreach($array['foreign_ids'] as $id){
      $__insert[] = [
        $array['local_id_name'] => $array['local_id'],
        $array['foreign_id_name'] => $id
      ];
    }
    $array['class']::insert($__insert);
  }
  public static function cacheRelatedToComponent($class, $component){
    // Get all the models that use this component
    $subq = $component->pacos()->select('paco_id');
    $models = $class::whereHas('pacos', function($query) use ($subq){
        $query->join(\DB::raw('('.$subq->toSql().')'.' as pid'), function($join){
          $join->on('pid.paco_id', '=', 'packed_components.paco_id');
        });
      })
      ->setBindings($subq->getBindings())
      ->get();

    // Re-make the cache on all the related models
    foreach($models as $m){
      $m->cache();
    }
  }
  public static function cacheRelatedToPaco($class, $paco){
    // Get all the models that use this paco
    $models = $class::whereHas('pacos', function($query) use ($paco){
        $query->where('packed_components.paco_id', $paco->paco_id);
      })
      ->get();

    // Re-make the cache on all the related models
    foreach($models as $m){
      $m->cache();
    }
  }

  public static function querySelectLang($query, $lang='pt', ...$fields){
    $lang = mb_strtolower($lang);
    $id = $query->getModel()->getKeyName();

    $__select = [];
    $__select[] = $id;
    foreach($fields as $field){
      if($lang == 'pt'){
        $__select[] = $field;
      }else if($lang == 'en'){
        $__select[] = $field.'_en as '.$field;
      }
    }
    $query->select($__select);

    return $query;
  }

  /* Text */
  public static function splitText($text, $_max_chars){
    $_split = preg_split('/\s+/', $text);
    $_lines = [];
    $_curr_line = 0;
    foreach($_split as $_word){
      if(strlen($_lines[$_curr_line]??'') + strlen($_word) <= $_max_chars){
        $_lines[$_curr_line] = ($_lines[$_curr_line] ?? '') . ' ' . $_word;
      }else{
        $_curr_line++;
        $_lines[$_curr_line] = ($_lines[$_curr_line] ?? '') . ' ' . $_word;
      }
    }
    return implode("\n", $_lines);
  }

  // Use to access objects with dot notation
  public static function dot_notation($obj, $dot){
    $data = $obj;
    $keys = explode(".", $dot);
    foreach($keys as $key){
      if(gettype($data) == "object"){
        if(!isset($data->$key)) return null;
        $data = $data->$key;
      }else if(gettype($data) == "array"){
        if(!isset($data[$key])) return null;
        $data = $data[$key];
      }else{
        return null;
      }
    }
    return $data;
  }

}
