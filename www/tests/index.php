<?php
//Load composer's autoloader
require_once '../html/vendor/autoload.php';

$config = new \releasecheck\Configuration();

$test = str_replace('.'.$config->basename(),'',strtolower($_SERVER['HTTP_HOST']));
$quickTest = isset($_GET['quickTest']);
$singleTest = isset($_GET['singleTest']);

$testSuite = $config->getExtendedClass('TestSuite');
$html = $config->getExtendedClass('HTML');

if ($testInfo = $testSuite->getTest($test)) {
  if (! $order = $testSuite->getOrder($test)) {
    $order = array ('last' => '', 'next' => 'result');
  }

  $IdPTest = $config->getExtendedClass('IdPCheck', array (
    $test,
    $testInfo['name'],
    $testInfo['tab'],
    $testInfo['expected'],
    $testInfo['nowarn']
  ));

  if ($quickTest) {
    $IdPTest->testAttributes($testInfo['subtest'], $order['next']);
  } else {
    $html->showHeaders($testInfo['name']);
    if ($test == 'mfa') {
       if (isset($_GET['forceAuthn'])) {
        $IdPTest->showTestHeaders('mfa','result',$singleTest);
      } else {
        $IdPTest->showTestHeaders('','mfa',$singleTest,true);
      }
    } else {
      $IdPTest->showTestHeaders($order['last'], $order['next'],$singleTest);
    }
    $IdPTest->testAttributes($testInfo['subtest']);
    $html->showFooter();
  }
} else {
  print "Unknown test : $test";
  exit;
}
