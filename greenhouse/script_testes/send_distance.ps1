$distance = Read-Host "Please enter your distance"

$result = Invoke-RestMethod  -uri https://localhost:7220/automation/sendsensordata -Method Post -Body @{micrcocontrollerID = "1"; type = "distance" ; value = "$distance"}

$expected = "micrcocontrollerID:1 type:distance value:$distance"

if($result.Equals($expected)){
    Write-Host "OK"
}else{
    Write-Host "TEST FAILD :
    Expected  : 
    $expected
    Actual    : 
    $result"
}
