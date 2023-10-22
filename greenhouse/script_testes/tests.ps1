
$result = Invoke-RestMethod  -uri https://localhost:7220/automation/sendsensordata -Method Post -Body @{micrcocontrollerID = "1"; type = "pH" ; value = "3,0"}

$expected = "micrcocontrollerID:1 type:pH value:3,0"

if($result.Equals($expected)){
    Write-Host "OK"
}else{
    Write-Host "TEST FAILD :
    Expected  : 
    $expected
    Actual    : 
    $result"
}



