<?php
namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Book;
use App\Models\Person;
use App\Models\Country;
use App\Models\BindType;
use App\Models\Language;
use App\Models\AdminPerm;
use App\Models\CoverType;

use App\Models\PaperType;
use App\Models\PrintType;
use App\Models\Publisher;
use App\Models\CountryCity;
use Illuminate\Http\Request;

use App\Models\FunctionModel;
use App\Models\ExtraCoverType;
use App\Models\PublicationType;

class SelectController_api extends Controller{
  use \App\Traits\ApiUtils;

  private function search($query, $name, $request){
    $page = $request->page - 1 ?? 0;

    $query = $query->where($name, 'LIKE', '%'.$request->q.'%');

    $count = (clone $query)->count();
    $query = $query->skip($page * self::PAGING)
    ->take(self::PAGING)
    ->get();
    return $this->apiResponseSelect($query, $count, self::PAGING);
  }

  const PAGING = 10;

  public function functions(Request $request){
    $query = FunctionModel::select('function_id as id', 'function_name as text');
    return $this->search($query, 'function_name', $request);
  }
  public function people(Request $request){
    $query = Person::select('person_id as id', 'person_name as text');
    return $this->search($query, 'person_name', $request);
  }
  public function languages(Request $request){
    $query = Language::select('language_code as id', 'language_name as text');
    return $this->search($query, 'language_name', $request);
  }
  public function countries(Request $request){
    $query = Country::select('country_code as id', 'country_name as text');
    return $this->search($query, 'country_name', $request);
  }
  public function countryCities(Request $request){
    $query = CountryCity::select('country_city_code as id', 'country_city_name as text');
    return $this->search($query, 'country_city_name', $request);
  }
  public function publicationTypes(Request $request){
    $query = PublicationType::select('publication_type_id as id', 'publication_type_name as text');
    return $this->search($query, 'publication_type_name', $request);
  }
  public function publishers(Request $request){
    $query = Publisher::select('publisher_id as id', 'publisher_name as text');
    return $this->search($query, 'publisher_name', $request);
  }
  public function tags(Request $request){
    $query = Tag::select('tag_id as id', 'tag_name as text');
    return $this->search($query, 'tag_name', $request);
  }
  public function coverTypes(Request $request){
    $query = CoverType::select('cover_type_id as id', 'cover_type_name as text');
    return $this->search($query, 'cover_type_name', $request);
  }
  public function extraCoverTypes(Request $request){
    $query = ExtraCoverType::select('extra_cover_type_id as id', 'extra_cover_type_name as text');
    return $this->search($query, 'extra_cover_type_name', $request);
  }
  public function paperTypes(Request $request){
    $query = PaperType::select('paper_type_id as id', 'paper_type_name as text');
    return $this->search($query, 'paper_type_name', $request);
  }
  public function printTypes(Request $request){
    $query = PrintType::select('print_type_id as id', 'print_type_name as text');
    return $this->search($query, 'print_type_name', $request);
  }
  public function bindTypes(Request $request){
    $query = BindType::select('bind_type_id as id', 'bind_type_name as text');
    return $this->search($query, 'bind_type_name', $request);
  }
  public function perms(Request $request){
    $query = AdminPerm::select('admin_perm_id as id', 'admin_perm_name as text');
    return $this->search($query, 'admin_perm_name', $request);
  }
  public function books(Request $request){
    $query = Book::select('book_id as id', 'book_title as text');
    return $this->search($query, 'book_title', $request);
  }
}
