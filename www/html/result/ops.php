<?php
const HTML_ACTIVE = ' active';
const HTML_CHECK_SP = 'check">   ';
const HTML_EXCLAMATION_SP = 'exclamation"> ';
const HTML_EXCLAMATION_TR_SP = 'exclamation-triangle">  ';

//Load composer's autoloader
require_once '../vendor/autoload.php';
$config = new \releasecheck\Configuration();

$htmlClass = $config->getExtendedClass('HTML');

$html = new $htmlClass();

$collapseIcons = array();
$tested_idps = array();
$tests = array(
  'RandS' => array(
    'displayName' => 'R&S',
    'fullName' => 'R&S',
    'dbName' => 'rands',
    'expected' => array (
      'ePPN' => 'eduPersonPrincipalName',
      'mail' => 'mail',
      'displayName' => 'displayName',
      'givenName' => 'givenName',
      'sn' => 'sn',
    ),
    'testResults' => array(
      'OKOK' => 'R&S attributes OK, Entity Category Support OK',
      'OKFail' => 'R&S attributes OK, Entity Category Support missing',
      'Fail' => 'R&S attribute missing, Entity Category Support missing',
      'FailFail' => 'R&S attributes missing, BUT Entity Category Support claimed',
    ),
  ),
  'CoCov1' => array(
    'displayName' => 'CoCov1',
    'fullName' => 'CoCov1',
    'dbName' => 'cocov1-1',
    'expected' => array (
        'norEduPersonNIN' => 'norEduPersonNIN',
        'personalIdentityNumber' => 'personalIdentityNumber',
    ),
    'testResults' => array(
      'OKOK' => 'CoCo OK, Entity Category Support OK',
      'OKFail' => 'CoCo OK, Entity Category Support missing',
      'Fail' => 'Support for CoCo missing, Entity Category Support missing',
      'FailFail' => 'CoCo is not supported, BUT Entity Category Support is claimed',
    ),
  ),
  'CoCov2' => array(
    'displayName' => 'CoCov2',
    'fullName' => 'CoCov2',
    'dbName' => 'cocov2-1',
    'expected' => array (
        'norEduPersonNIN' => 'norEduPersonNIN',
        'personalIdentityNumber' => 'personalIdentityNumber',
    ),
    'testResults' => array(
      'OKOK' => 'CoCo OK, Entity Category Support OK',
      'OKFail' => 'CoCo OK, Entity Category Support missing',
      'Fail' => 'Support for CoCo missing, Entity Category Support missing',
      'FailFail' => 'CoCo is not supported, BUT Entity Category Support is claimed',
    ),
  ),
  'Anon' => array(
    'displayName' => 'Anon',
    'fullName' => 'Anonymous',
    'dbName' => 'anonymous',
    'expected' => array (
        'ePSA' => 'eduPersonScopedAffiliation',
        'sHO' => 'schacHomeOrganization',
    ),
    'testResults' => array(
      'OKOK' => 'Anonymous attributes OK, Entity Category Support OK',
      'OKFail' => 'Anonymous attributes OK, Entity Category Support missing',
      'Fail' => 'Anonymous attribute missing, Entity Category Support missing',
      'FailFail' => 'Anonymous attributes missing, BUT Entity Category Support claimed',
    ),
  ),
  'PAnon' => array(
    'displayName' => 'Panon',
    'fullName' => 'Pseudonymous',
    'dbName' => 'pseudonymous',
    'expected' => array (
        'pairwise-id' => 'pairwise-id',
        'ePA' => 'eduPersonAssurance',
        'ePSA'=> 'eduPersonScopedAffiliation',
        'sHO' => 'schacHomeOrganization',
    ),
    'testResults' => array(
      'OKOK' => 'Pseudonymous attributes OK, Entity Category Support OK',
      'OKFail' => 'Pseudonymous attributes OK, Entity Category Support missing',
      'Fail' => 'Pseudonymous attribute missing, Entity Category Support missing',
      'FailFail' => 'Pseudonymous attributes missing, BUT Entity Category Support claimed',
    ),
  ),
  'Pers' => array(
    'displayName' => 'Pers',
    'fullName' => 'Personalized',
    'dbName' => 'personalized',
    'expected' => array (
      'subject-id' => 'subject-id',
      'mail' => 'mail',
      'displayName' => 'displayName',
      'givenName' => 'givenName',
      'sn' => 'sn',
      'ePA' => 'eduPersonAssurance',
      'ePSA' => 'eduPersonScopedAffiliation',
      'sHO' => 'schacHomeOrganization',
    ),
    'testResults' => array(
      'OKOK' => 'Personalized attributes OK, Entity Category Support OK',
      'OKFail' => 'Personalized attributes OK, Entity Category Support missing',
      'Fail' => 'Personalized attribute missing, Entity Category Support missing',
      'FailFail' => 'Personalized attributes missing, BUT Entity Category Support claimed',
    ),
  ),
);

if (isset($config->getFederation()['metadataTool'])) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://'.$config->getFederation()['metadataTool'].'/api/v1/');
  curl_setopt($ch, CURLOPT_USERAGENT, 'Release-check');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_NOBODY, 0);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  $res = curl_exec($ch);
  $data = json_decode($res, true, 4);
  foreach ($data['objects'] as $row) {
    $tested_idps[$row['entityID']] = false;
  }
  curl_close($ch);
}

$tab = isset($_GET['tab']) ? $_GET['tab'] : '';
$html->showHeaders();
if (! in_array($_SERVER['saml_eduPersonPrincipalName'], $config->getFederation()['adminUsers'] )) {
    print '<h1>No access</h1>';
    $html->showFooter();
    exit;
}
printf('    <div class="row">
      <div class="col">
        <ul class="nav nav-tabs">%s', "\n");
foreach ($tests as $test => $data) {
  printf('          <li class="nav-item">
            <a class="nav-link%s" href="?tab=%s">%s</a>
          </li>%s',
    $tab == $test ? HTML_ACTIVE : '',
    $test, $data['displayName'], "\n");
}
printf('          <li class="nav-item">
            <a class="nav-link%s" href="?tab=MFA">MFA</a>
          </li>
          <li class="nav-item">
            <a class="nav-link%s" href="?tab=ESI">ESI</a>
          </li>
          <li class="nav-item">
            <a class="nav-link%s" href="?tab=AllTests">AllTests</a>
          </li>
          <li class="nav-item">
            <a class="nav-link%s" href="?tab=ECS">ECS</a>
          </li>
        </ul>
      </div>
    </div>%s',
  $tab == 'MFA' ? HTML_ACTIVE : '', $tab == 'ESI' ? HTML_ACTIVE : '',
  $tab == 'AllTests' ? HTML_ACTIVE : '', $tab == 'ECS' ? HTML_ACTIVE : '', "\n");
if (isset($_GET['idp'])) {
  printf ('        <h3>Result for %s</h3>%s', "\n", htmlspecialchars($_GET['idp']));
  switch ($tab) {
    case 'MFA' :
      showTestsIdP('mfa');
      break;
    case 'ESI' :
      showTestsIdP('esi');
      break;
    default :
      showTestsIdP();
  }
} elseif ($tab != '') {
  if (isset($tests[$tab])) {
    showTab($tab, $tests[$tab], $tested_idps);
  } else {
    switch ($tab) {
      case 'MFA' :
        showMFA($tested_idps);
        break;
      case 'ESI' :
        showESI($tested_idps);
        break;
      case 'AllTests' :
        showAllTests();
        break;
      case 'ECS' :
        showEcsStatus();
        break;
      default :
    }
  }
  $html->addTableSort('resultTable');
}

$html->showFooter();

function sends($string,$attribute) {
  return strpos($string, $attribute) !== false;
}

function showTab($tab, $data,$tested_idps) {
  printf('    <div class="row">
      <div class="col">
        <h1>Data based on IdP:s that have run %s test</h1>
        <table class="table table-striped table-bordered">
          <tr>
            <td>%s data </td>
            <td>%s',
    $data['fullName'], $data['fullName'], "\n");
  switch ($tab) {
    case 'CoCov1' :
    case 'CoCov2' :
      printf('              <i class="fas fa-check"> = Only send reqested data or less</i><br>
              <i class="fas fa-exclamation-triangle"> = Only send reqested data or less (not sending norEduPersonNIN)</i><br>
              <i class="fas fa-exclamation"> = Send to much data</i>%s', "\n");
      break;
    default :
      printf('              <i class="fas fa-check"> = Only send reqested data</i><br>
              <i class="fas fa-exclamation"> = Send to much/less data</i>%s', "\n");
  }
  printf('            </td>
          </tr>
          <tr>
            <td>%s ECS</td>
            <td>
              <i class="fas fa-check"> = Have ECS for %s</i><br>
              <i class="fas fa-exclamation-triangle"> = Missing ECS for %s</i><br>
              <i class="fas fa-exclamation"> = Have ECS for %s but sends to much data > not %s</i>
            </td>
          </tr>
          <tr>
            <td>%s</td>
            <td>
              <i class="fas fa-check"> = Sends attribute</i><br>
              <i class="fas fa-exclamation"> = Doesn\'t send attribute</i>
            </td>
          </tr>
        </table>
        <br>
        <table id="resultTable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>IdP</th>
              <th>Tested</th>
              <th>Data</th>
              <th>ECS</th>%s',
    $data['fullName'], $data['fullName'], $data['fullName'], $data['fullName'], $data['fullName'],
    implode('<br>', $data['expected']), "\n");
  foreach ($data['expected'] as $shortName => $SAML) {
    printf('              <th>%s</th>%s', $shortName, "\n");
  }
  printf('            </tr>
          </thead>
          <tbody>%s', "\n");
  global $config;
  $testHandler = $config->getDB()->prepare(
    'SELECT `entityID`, `attr_OK`, `testResult`, `tests`.`time`
    FROM `tests`, `testRuns`, `idps`
    WHERE `tests`.`testRun_id` = `testRuns`.`id`
      AND `testRuns`.`idp_id` = `idps`.`id`
      AND `test` = :Test
    ORDER BY `entityID`;');
  $okData=0;
  $warnData=0;
  $failData=0;
  $okEC=0;
  $warnEC=0;
  $failEC=0;
  $testHandler->execute(array('Test' => $data['dbName']));
  while ($testResult=$testHandler->fetch(PDO::FETCH_ASSOC)) {
    $idp = $testResult['entityID'];
    $tested_idps[$idp] = true;

    printf('            <tr>
              <td><a href="?tab=%s&idp=%s">%s</a></td>
              <td>%s</td>%s', $tab, $idp, $idp, $testResult['time'], "\n");
    switch ($testResult['testResult']) {
      case $data['testResults']['OKOK'] :
        printf('              <td><i class="fas fa-check">   </td>
              <td><i class="fas fa-check">   </td>%s', "\n");
        $okData++;
        $okEC++;
        break;
      case $data['testResults']['OKFail'] :
        printf('              <td><i class="fas fa-check">   </td>
              <td><i class="fas fa-exclamation-triangle">  </td>%s', "\n");
        $okData++;
        $warnEC++;
        break;
      case $data['testResults']['Fail'] :
        printf('              <td><i class="fas fa-exclamation"> </td>
              <td></td>%s', "\n");
        $failData++;
        break;
      case $data['testResults']['FailFail'] :
        printf('              <td><i class="fas fa-exclamation"> </td>
              <td><i class="fas fa-exclamation"> </td>%s', "\n");
        $failData++;
        $failEC++;
        break;
      default :
        printf('              <td>%s</td>
              <td></td>%s', $testResult['testResult'], "\n");
    }
    foreach ($data['expected'] as $SAML) {
      printf('              <td><i class="fas fa-%s</td>%s',
      sends($testResult['attr_OK'],$SAML) ? HTML_CHECK_SP : HTML_EXCLAMATION_SP, "\n");
    }
    printf('            </tr>%s', "\n");
  }
  printFooterSummary($okData, $warnData, $failData, $okEC, $warnEC, $failEC, $tested_idps);
}

function showMFA($tested_idps) {
  print '    <div class="row">
      <div class="col">
        <h1>Data based on IdP:s that have run MFA test</h1>
        <table class="table table-striped table-bordered">
          <tr>
            <td>MFA </td>
            <td>
              <i class="fas fa-check"> = Responds with REFEDS MFA</i><br>
              <i class="fas fa-exclamation"> = Wrongly sends something else (SHOULD break an not return anything)</i>
            </td>
          </tr>
          <tr>
            <td>ForceAuthn</td>
            <td>
              <i class="fas fa-check"> = Sends a new Authentication-Instant in step 2</i><br>
              <i class="fas fa-exclamation"> = Sends same Authentication-Instant in step 2</i>
            </td>
          </tr>
        </table>
        <br>
        <br>
        <table id="resultTable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>IdP</th>
              <th>Tested</th>
              <th>MFA</th>
              <th>ForceAuthn</th>
            </tr>
          </thead>
          <tbody>' . "\n";
  global $config;
  $testHandler = $config->getDB()->prepare(
    "SELECT `entityID`, `attr_OK`, `testResult`, `tests`.`time`
    FROM `tests`, `testRuns`, `idps`
    WHERE `tests`.`testRun_id` = `testRuns`.`id`
      AND `testRuns`.`idp_id` = `idps`.`id`
      AND `test` = 'mfa'
    ORDER BY `entityID`;");
  $okMFA = 0;
  $okForceAuthn = 0;
  $failMFA = 0;
  $failForceAuthn = 0;
  $testHandler->execute();
  while ($testResult=$testHandler->fetch(PDO::FETCH_ASSOC)) {
    $idp = $testResult['entityID'];
    $tested_idps[$idp] = true;

    printf('            <tr>
              <td><a href="?tab=MFA&idp=%s">%s</a></td>
              <td>%s</td>%s', $idp, $idp, $testResult['time'], "\n");
    switch ($testResult['testResult']) {
      case 'Supports REFEDS MFA and ForceAuthn.' :
        printf('              <td><i class="fas fa-check"></i> OK</td>
              <td><i class="fas fa-check"></i> OK</td>%s', "\n");
        $okMFA++;
        $okForceAuthn++;
        break;
      case 'Does support ForceAuthn but not REFEDS MFA.' :
        printf('              <td><i class="fas fa-exclamation"></i> Fail</td>
              <td><i class="fas fa-check"></i> OK</td>%s', "\n");
        $failMFA++;
        $okForceAuthn++;
        break;
      case 'Supports REFEDS MFA but not ForceAuthn.' :
        printf('              <td><i class="fas fa-check"></i> OK</td>\n";
              <td><i class="fas fa-exclamation"></i> Fail</td>%s', "\n");
        $okMFA++;
        $failForceAuthn++;
        break;
      case 'Does neither support REFEDS MFA or ForceAuthn.' :
        printf('              <td><i class="fas fa-exclamation"></i> Fail</td>\n";
              <td><i class="fas fa-exclamation"></i> Fail</td>%s', "\n");
        $failMFA++;
        $failForceAuthn++;
        break;
      default :
        printf('              <td>%s</td>%s',$testResult['testResult'], "\n");
    }
    print "            </tr>\n";
  }
  printFooterSummary($okMFA, 0, $failMFA, $okForceAuthn, 0, $failForceAuthn, $tested_idps);
}

function showESI($tested_idps) {
  print '    <div class="row">
      <div class="col">
        <h1>Data based on IdP:s that have run ESI test</h1>
        <i class="fas fa-check"> = Correct schacPersonalUniqueCode</i><br>
        <i class="fas fa-exclamation-triangle"> = Missing schacPersonalUniqueCode or to many</i><br>
        <i class="fas fa-exclamation"> = Error in schacPersonalUniqueCode</i>
        <br>
        <br>
        <table id="resultTable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>IdP</th>
              <th>Tested</th>
              <th>ESI (any)</th>
              <th>Tested</th>
              <th>ESI (as student)</th>
            </tr>
          </thead>
          <tbody>' . "\n";
  global $config;
  $testRun = 0;

  $testHandler = $config->getDB()->prepare(
    "SELECT `entityID`, `attr_OK`, `testResult`, `tests`.`time`, `testRun_id`
    FROM `tests`, `testRuns`, `idps`
    WHERE `tests`.`testRun_id` = `testRuns`.`id`
      AND `testRuns`.`idp_id` = `idps`.`id`
      AND `test` = 'esi'
    ORDER BY `entityID`;");
  $testStudHandler = $config->getDB()->prepare(
    "SELECT `attr_OK`, `testResult`, `tests`.`time`
    FROM `tests` WHERE `testRun_id` = :testrun
      AND `test` = 'esi-stud'");
  $testStudHandler->bindParam(':testrun', $testRun);

  $ok=0;
  $warn=0;
  $fail=0;
  $okStud=0;
  $warnStud=0;
  $failStud=0;
  $testHandler->execute();
  while ($testResult=$testHandler->fetch(PDO::FETCH_ASSOC)) {
    $idp = $testResult['entityID'];
    $testRun = $testResult['testRun_id']; // NO SONAR bound above
    $tested_idps[$idp] = true;

    printf('            <tr>
              <td><a href="?tab=ESI&idp=%s">%s</a></td>
              <td>%s</td>%s', $idp, $idp, $testResult['time'], "\n");
    switch ($testResult['testResult']) {
      case 'schacPersonalUniqueCode OK':
        print "              <td><i class=\"fas fa-check\"></i> OK</td>\n";
        $ok++;
        break;
      case 'schacPersonalUniqueCode OK. BUT wrong case':
        print "              <td><i class=\"fas fa-check\"></i> OK, <i class=\"fas fa-exclamation-triangle\"></i> Wrong case</td>\n";
        $ok++;
        break;
      case 'Missing schacPersonalUniqueCode':
        print "              <td><i class=\"fas fa-exclamation-triangle\"></i> No schacPersonalUniqueCode</td>\n";
        $warn++;
        break;
      case 'More than one schacPersonalUniqueCode';
        print "              <td><i class=\"fas fa-exclamation-triangle\"></i> More than one schacPersonalUniqueCode</td>\n";
        $warn++;
        break;
      case 'schacPersonalUniqueCode not starting with urn:schac:personalUniqueCode:int:esi:';
        print "              <td><i class=\"fas fa-exclamation\"></i> Not correct code</td>\n";
        $fail++;
        break;
      case 'schacPersonalUniqueCode starting with urn:schac:personalUniqueCode:int:esi:se:';
        print "              <td><i class=\"fas fa-exclamation\"></i> sHO = se</td>\n";
        $fail++;
        break;
      default :
        print "              <td>" . $testResult['testResult'] . "</td>\n";
    }
    $testStudHandler->execute();
    if ($testResult = $testStudHandler->fetch(PDO::FETCH_ASSOC)) {
      printf("              <td>%s</td>\n",$testResult['time']);
      switch ($testResult['testResult']) {
        case 'schacPersonalUniqueCode OK':
          print "              <td><i class=\"fas fa-check\"></i> OK</td>\n";
          $okStud++;
          break;
        case 'schacPersonalUniqueCode OK. BUT wrong case':
          print "              <td><i class=\"fas fa-check\"></i> OK, <i class=\"fas fa-exclamation-triangle\"></i> Wrong case</td>\n";
          $okStud++;
          break;
        case 'Missing schacPersonalUniqueCode':
          print "              <td><i class=\"fas fa-exclamation-triangle\"></i> No schacPersonalUniqueCode</td>\n";
          $warnStud++;
          break;
        case 'More than one schacPersonalUniqueCode';
          print "              <td><i class=\"fas fa-exclamation-triangle\"></i> More than one schacPersonalUniqueCode</td>\n";
          $warnStud++;
          break;
        case 'schacPersonalUniqueCode not starting with urn:schac:personalUniqueCode:int:esi:';
          print "              <td><i class=\"fas fa-exclamation\"></i> Not correct code</td>\n";
          $failStud++;
          break;
        case 'schacPersonalUniqueCode starting with urn:schac:personalUniqueCode:int:esi:se:';
          print "              <td><i class=\"fas fa-exclamation\"></i> sHO = se</td>\n";
          $failStud++;
          break;
        default :
          print "              <td>" . $testResult['testResult'] . "</td>\n";
      }
    } else {
      print '              <td>No test run as Student</td>
              <td></td>' . "\n";
    }
    print "            </tr>\n";
  }
  printf('          </tbody>
          <tfooter>
            <tr>
              <td></td>
              <td></td>
              <td>%s', "\n");
  if ($ok) printf("                <i class=\"fas fa-check\"></i> = %s<br>\n",$ok);
  if ($warn) printf("                <i class=\"fas fa-exclamation-triangle\"></i> = %s<br>\n",$warn);
  if ($fail) printf("                <i class=\"fas fa-exclamation\"></i> = %s<br>\n",$fail);
  printf('              </td>
              <td></td>
              <td>%s', "\n");
  if ($okStud) printf("                <i class=\"fas fa-check\"></i> = %s<br>\n",$okStud);
  if ($warnStud) printf("                <i class=\"fas fa-exclamation-triangle\"></i> = %s<br>\n",$warnStud);
  if ($failStud) printf("                <i class=\"fas fa-exclamation\"></i> = %s<br>\n",$failStud);
  printf('              </td>
            </tr>
          <tfooter>
        </table>%s', "\n");
  print('        <table class="table table-striped table-bordered">'. "\n");
  printf ("          <tr><th>SWAMID 2.0 IdP:s not tested</th></tr>\n");
  foreach ($tested_idps as $idp => $value) {
    if (! $value ) {
      printf ("          <tr><td>%s</td></tr>\n", $idp);
    }
  }
  print "        </table>
      </div><!-- End col-->
    </div><!-- End row-->\n";
}

function showAllTests() {
  global $config;
  $lastYear = date('Y-m-d', mktime(0, 0, 0, date('m'),   date('d'),   date('Y')-1));

  $tests = array('assurance', 'noec', 'anonymous', 'pseudonymous', 'personalized', 'cocov2-1', 'cocov2-2', 'cocov2-3', 'cocov1-1', 'cocov1-2', 'cocov1-3', 'rands', 'mfa', 'esi');

  $idpHandler = $config->getDB()->prepare("SELECT DISTINCT `id`, `entityID` FROM `idps` ORDER BY `entityID`;");
  $testHandler = $config->getDB()->prepare(
    "SELECT `status_OK`, `status_WARNING`, `status_ERROR`, `tests`.`time`
    FROM `tests`, `testRuns`
    WHERE `tests`.`testRun_id` = `testRuns`.`id`
      AND `testRuns`.`idp_id` = :idpId
      AND `test` = :test
      ORDER BY `time` DESC;");
  $testHandler->bindParam(":test",$test);
  print '    <div class="row">
      <div class="col">
        <h1>Data based on IdP:s that have run any of the tests</h1>
        <p>Result inside () is older than one year.</p>
        <table id="resultTable" class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>IdP</th>
              <th>Assurance</th>
              <th>No&nbsp;EC</th>
              <th>Anonymous</th>
              <th>Pseudonymous</th>
              <th>Personalized</th>
              <th>CoCo v2 part 1</th>
              <th>CoCo v2 part 2</th>
              <th>CoCo v2, outside</th>
              <th>CoCo v1 part 1</th>
              <th>CoCo v1 part 2</th>
              <th>CoCo v1, outside</th>
              <th>REFEDS R&S</th>
              <th>MFA</th>
              <th>ESI</th>
            </tr>
          <thead>
          <tbody>' . "\n";

  $idpHandler->execute();
  while ($idp = $idpHandler->fetch(PDO::FETCH_ASSOC)) {
    $testHandler->bindValue(":idpId",$idp['id']);
    printf ('            <tr>
              <td><a href="?tab=AllTests&idp=%s">%s</a></td>', $idp['entityID'], $idp['entityID'], "\n");
    foreach ($tests as $test) {
      $testHandler->execute();
      if ($testResult=$testHandler->fetch(PDO::FETCH_ASSOC)) {
        printf('              <td>%s', $testResult['time']> $lastYear ? '' : '(');
        print $testResult['status_OK'] ? "<i class=\"fas fa-check\"></i>" : '';
        print $testResult['status_WARNING'] ? "<i class=\"fas fa-exclamation-triangle\"></i>" : '';
        print $testResult['status_ERROR'] ?"<i class=\"fas fa-exclamation\"></i>" : '';
        printf('%s</td>%s', $testResult['time']> $lastYear ? '' : ')', "\n");
      } else {
        print "              <td></td>\n";
      }
    }
    print "            </tr>\n";
  }
  print "          <tbody>
        </table>
      </div><!-- End col-->
    </div><!-- End row-->\n";
}

function showEcsStatus() {
  global $config;
  $lastYear = date('Y-m-d', mktime(0, 0, 0, date('m'),   date('d'),   date('Y')-1));

  $tests = array('anonymous', 'pseudonymous', 'personalized', 'cocov2-1', 'cocov1-1', 'rands');

  $idpHandler = $config->getDB()->prepare("SELECT DISTINCT `id`, `entityID` FROM `idps` ORDER BY `entityID`;");
  $testHandler = $config->getDB()->prepare(
    "SELECT `testResult`, `tests`.`time`
    FROM `tests`, `testRuns`
    WHERE `tests`.`testRun_id` = `testRuns`.`id`
      AND `testRuns`.`idp_id` = :idpId
      AND `test` = :test
      ORDER BY `time` DESC;");
  $testHandler->bindParam(":test",$test);
  print '    <div class="row">
      <div class="col">
        <h1>Data based on IdP:s that have run any of the tests</h1>
        <p>Result inside () is older than one year.</p>
        <table class="table table-striped table-bordered">
          <tr>
            <th>IdP</th>
            <th>Anonymous</th>
            <th>Pseudonymous</th>
            <th>Personalized</th>
            <th>CoCo v2</th>
            <th>CoCo v1</th>
            <th>REFEDS R&S</th>
            <th>ESI</th>
          </tr>' . "\n";

  $idpHandler->execute();
  while ($idp=$idpHandler->fetch(PDO::FETCH_ASSOC)) {
    $testHandler->bindValue(":idpId",$idp['id']);
    printf ("          <tr>\n            <td><a href=\"?tab=AllTests&idp=%s\">%s</a></td>\n", $idp['entityID'],$idp['entityID']);
    foreach ($tests as $test) {
      $testHandler->execute();
      if ($testResult=$testHandler->fetch(PDO::FETCH_ASSOC)) {
        printf('            <td>%s', $testResult['time']> $lastYear ? '' : '(');
        switch ($testResult['testResult']) {
          case 'Anonymous attributes OK, Entity Category Support OK' :
          case 'Pseudonymous attributes OK, Entity Category Support OK' :
          case 'Personalized attributes OK, Entity Category Support OK' :
          case 'CoCo OK, Entity Category Support OK':
          case 'R&S attributes OK, Entity Category Support OK' :
            print '<i class="fas fa-check"><i class="fas fa-check">';
            break;
          case 'Anonymous attributes OK, Entity Category Support missing' :
          case 'Pseudonymous attributes OK, Entity Category Support missing' :
          case 'Personalized attributes OK, Entity Category Support missing' :
          case 'CoCo OK, Entity Category Support missing' :
          case 'R&S attributes OK, Entity Category Support missing' :
            print '<i class="fas fa-check"><i class="fas fa-exclamation-triangle">';
            break;
          case 'Anonymous attribute missing, Entity Category Support missing' :
          case 'Pseudonymous attribute missing, Entity Category Support missing' :
          case 'Personalized attribute missing, Entity Category Support missing' :
          case 'Support for CoCo missing, Entity Category Support missing':
          case 'R&S attribute missing, Entity Category Support missing' :
            print '<i class="fas fa-exclamation"><i class="fas fa-exclamation-triangle">';
            break;
          case 'Anonymous attributes missing, BUT Entity Category Support claimed';
          case 'Pseudonymous attributes missing, BUT Entity Category Support claimed';
          case 'Personalized attributes missing, BUT Entity Category Support claimed';
          case 'CoCo is not supported, BUT Entity Category Support is claimed';
          case 'R&S attributes missing, BUT Entity Category Support claimed';
            print '<i class="fas fa-exclamation"><i class="fas fa-exclamation">';
            break;
          default :
            print $testResult['testResult'];
        }
        printf('%s</td>%s', $testResult['time']> $lastYear ? '' : ')', "\n");
      } else
        print "            <td></td>\n";
    }
    $esiStatus = '';
    $esiTime = '';
    $test = 'esi-stud';
    $testHandler->execute();
    if ($testResult=$testHandler->fetch(PDO::FETCH_ASSOC)) {
      switch ($testResult['testResult']) {
        case 'schacPersonalUniqueCode OK' :
          $esiStatus = 'check';
          break;
        case 'Missing schacPersonalUniqueCode' :
          $esiStatus = 'exclamation-triangle';
          break;
        default :
          print $testResult['testResult'];
      }
      $esiTime = $testResult['time'];
    }
    if ($esiStatus <> 'check') {
      $test = 'esi';
      $testHandler->execute();
      if ($testResult=$testHandler->fetch(PDO::FETCH_ASSOC)) {
        switch ($testResult['testResult']) {
          case 'schacPersonalUniqueCode OK' :
            $esiStatus = 'check';
            break;
          case 'Missing schacPersonalUniqueCode' :
            $esiStatus = 'exclamation-triangle';
            break;
          default :
            print $testResult['testResult'];
        }
        $esiTime = $testResult['time'];
      }
    }
    if ($esiStatus == '') {
      print "            <td></td>\n";
    } else {
      printf('            <td>%s<i class="fas fa-%s">%s</td>%s', $esiTime > $lastYear ? '' : '(', $esiStatus, $testResult['time']> $lastYear ? '' : ')', "\n");
    }
    print "          </tr>\n";
  }
  print "        </table>
      </div><!-- End col-->
    </div><!-- End row-->\n";
}

function showTestsIdP($test='entityCategory') {
  $display = new \releasecheck\Display();
  $idp = $_GET['idp'];
  if ($testruns = $display->getTestruns($idp, $test)) {
    $testrun = $testruns[0];
    if (count($testruns) > 1) {
      print "          <h4>Other results</h4>
        <ul>\n";
      foreach($testruns as $run) {
        printf('            <li><a href="./ops.php?tab=%s&idp=%s&id=%d">%s</a></li>%s', urlencode($_GET['tab']), urlencode($idp), $run['id'], $run['time'], "\n");
        # Check if thus run is requested run. In that case save this run
        if (isset($_GET['id']) && $_GET['id'] == $run['id']) {
          $testrun = $run;
        }
      }
      print "          </ul>\n";
    }
  } else {
    $testrun = array ('id' => 0, 'time' => 'no run');
  }
  switch ($test) {
    case 'mfa' :
      $display->showResultsMFA($idp, $testrun['id']);
      break;
    case 'esi' :
      $display->showResultsESI($idp, $testrun['id']);
      break;
    case 'entityCategory' :
    default :
      $display->showResultsECTests($idp, $testrun['id']);
      break;
  }
}

function printFooterSummary($okData, $warnData, $failData, $okEC, $warnEC, $failEC, $tested_idps) {
  printf('          </tbody>
          <tfooter>
            <tr>
              <td></td>
              <td></td>
              <td>%s', "\n");
  if ($okData) printf("                <i class=\"fas fa-check\"></i> = %s<br>\n",$okData);
  if ($warnData) printf("                <i class=\"fas fa-exclamation-triangle\"></i> = %s<br>\n",$warnData);
  if ($failData) printf("                <i class=\"fas fa-exclamation\"></i> = %s<br>\n",$failData);
  printf('              </td>
              <td>%s', "\n");
  if ($okEC) printf("                <i class=\"fas fa-check\"></i> = %s<br>\n",$okEC);
  if ($warnEC) printf("                <i class=\"fas fa-exclamation-triangle\"></i> = %s<br>\n",$warnEC);
  if ($failEC) printf("                <i class=\"fas fa-exclamation\"></i> = %s<br>\n",$failEC);
  printf('              </td>
            </tr>
          </tfooter>
        </table>
        <table class="table table-striped table-bordered">
          <tr><th>SWAMID 2.0 IdP:s not tested</th></tr>%s', "\n");
  foreach ($tested_idps as $idp => $value) {
    if (! $value ) {
      printf ('          <tr><td>%s</a></td></tr>%s', $idp, "\n");
    }
  }
  printf('        </table>
      </div><!-- End col-->
    </div><!-- End row-->%s', "\n");
}
