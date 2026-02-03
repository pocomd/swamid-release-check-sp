<?php
namespace releasecheck;

class TestSuiteSWAMID extends TestSuite {
  protected const DESC_SUBJECTID_NOWARN = 'Its value for a given subject is independent of the relying party to whom it is given (not recomended for this test, but should be sent if pairwise-id isn\'t sent) .';

  /**
   * Tests that should be in the list for EntityCategory tests
   */
  protected $ecTests = array(
    'assurance',
    'noec',
    'anonymous',
    'pseudonymous',
    'personalized',
    'cocov2-1',
    'cocov2-2',
    'cocov2-3',
    'cocov1-1',
    'cocov1-2',
    'cocov1-3',
    'rands',
  );

  /**
   * Setup the class
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();

    /**
     * Changes in order from TestSuite
     */

    $this->order['personalized']['next'] = 'cocov2-1';
    $this->order['cocov1-1'] = array (
      'last' => 'cocov2-3',
      'next' => 'cocov1-2',
    );
    $this->order['cocov1-2'] = array (
      'last' => 'cocov1-1',
      'next' => 'cocov1-3',
    );
    $this->order['cocov1-3'] = array (
      'last' => 'cocov1-2',
      'next' => 'rands',
    );
    $this->order['cocov2-1'] = array (
      'last' => 'personalized',
      'next' => 'cocov2-2',
    );
    $this->order['cocov2-2'] = array (
      'last' => 'cocov2-1',
      'next' => 'cocov2-3',
    );
    $this->order['cocov2-3'] = array (
      'last' => 'cocov2-2',
      'next' => 'cocov1-1',
    );
    $this->order['rands']['last'] = 'cocov1-3';

    /**
     * Changes from tests in TestSuite
     */
    $this->tests['assurance']['expected']['eduPersonAssurance'] =
     'User assurance information. SWAMID Identity Assurance Profiles can only be asserted for a user if and only if both the organisation and the user is validated for the assurance level. Furthermore, REFEDS Assurance Framework information should be released based on SWAMID Assurance level for the user.';

    $this->tests['mfa']['name'] = 'SWAMID MFA Check';
    $this->tests['mfa']['expected']['eduPersonAssurance'] = $this->tests['assurance']['expected']['eduPersonAssurance'];

    $this->tests['pseudonymous']['expected']['eduPersonAssurance'] = $this->tests['assurance']['expected']['eduPersonAssurance'];

    $this->tests['personalized']['expected']['eduPersonAssurance'] = $this->tests['assurance']['expected']['eduPersonAssurance'];

    // Extra attribute for SWAMID test
    $this->tests['rands']['expected']['eduPersonAssurance'] = $this->tests['assurance']['expected']['eduPersonAssurance'];

    // Added tests for swamid
    $this->tests['cocov1-1'] = array (
      'name'     => 'GÉANT CoCo part 1, from SWAMID',
      'tab'      => 'entityCategory',
      'expected' => array (
        'eduPersonPrincipalName'     => self::DESC_EDUPERSONPRINCIPALNAME,
        'eduPersonOrcid'             => self::DESC_EDUPERSONORCID,
        'schacDateOfBirth'           => self::DESC_SCHACDATEOFBIRTH,
        'displayName'                => self::DESC_DISPLAYNAME,
        'cn'                         => self::DESC_CN,
        'givenName'                  => self::DESC_GIVENNAME,
        'sn'                         => self::DESC_SN,
        'eduPersonAssurance'         => self::DESC_EDUPERSONASSURANCE,
        'eduPersonScopedAffiliation' => self::DESC_EDUPERSONSCOPEDAFFILIATION,
        'eduPersonAffiliation'       => self::DESC_EDUPERSONAFFILIATION,
        'schacHomeOrganizationType'  => self::DESC_SCHACHOMEORGANIZATIONTYPE,
        'norEduPersonNIN'            => '12 digit Socialsecuritynumber. Same as for example LADOK uses. Required for systems like LADOK to work.',
        'personalIdentityNumber'     => 'Swedish 12 digit Socialsecuritynumber. Same as in passport',
        'eduPersonAssurance'         => $this->tests['assurance']['expected']['eduPersonAssurance'],
      ),
      'nowarn'   => array (
        'persistent-id' => self::DESC_PERSISTENTID,
        'transient-id'  => self::DESC_TRANSIENTID,
      ),
      'subtest'  => 'CoCov1',
    );
    $this->tests['cocov1-2'] = array (
      'name'     => 'GÉANT CoCo part 2, from SWAMID',
      'tab'      => 'entityCategory',
      'expected' =>array (
        'eduPersonPrincipalName' => self::DESC_EDUPERSONPRINCIPALNAME,
        'mail'                   => self::DESC_MAIL,
        'displayName'            => self::DESC_DISPLAYNAME,
        'cn'                     => self::DESC_CN,
        'givenName'              => self::DESC_GIVENNAME,
        'sn'                     => self::DESC_SN,
        'o'                      => self::DESC_O,
        'norEduOrgAcronym'       => self::DESC_NOREDUORGACRONYM,
        'c'                      => self::DESC_C,
        'co'                     => self::DESC_CO,
        'schacHomeOrganization'  =>self::DESC_SCHACHOMEORGANIZATION,
      ),
      'nowarn'   => array (
        'persistent-id' => self::DESC_PERSISTENTID,
        'transient-id'  => self::DESC_TRANSIENTID,
      ),
      'subtest'  => 'CoCov1',
    );
    $this->tests['cocov1-3'] = array (
      'name'     => 'GÉANT CoCo, from outside SWAMID (requests civic number (personnummer) but this SHOULD NOT be released)',
      'tab'      => 'entityCategory',
      'expected' =>array (
        'eduPersonPrincipalName' => self::DESC_EDUPERSONPRINCIPALNAME,
        'displayName'            => self::DESC_DISPLAYNAME,
        'cn'                     => self::DESC_CN,
        'givenName'              => self::DESC_GIVENNAME,
        'schacDateOfBirth'       => self::DESC_SCHACDATEOFBIRTH,
        'sn'                     => self::DESC_SN,
        'mail'                   => self::DESC_MAIL,
      ),
      'nowarn'   => array (
        'persistent-id' => self::DESC_PERSISTENTID,
        'transient-id'  => self::DESC_TRANSIENTID,
      ),
      'subtest'  => 'CoCov1',
    );

    # Rename test cocov2-1 to cocov2
    $this->tests['cocov2-1'] = $this->tests['cocov2'];
    unset($this->tests['cocov2']);
    $this->tests['cocov2-1']['name'] = 'REFEDS CoCo part 1, from SWAMID';
    $this->tests['cocov2-1']['expected']['norEduPersonNIN'] = $this->tests['cocov1-1']['expected']['norEduPersonNIN'];
    $this->tests['cocov2-1']['expected']['personalIdentityNumber'] = $this->tests['cocov1-1']['expected']['personalIdentityNumber'];
    $this->tests['cocov2-1']['expected']['eduPersonAssurance'] = $this->tests['assurance']['expected']['eduPersonAssurance'];

    $this->tests['cocov2-2'] = array (
      'name'     => 'REFEDS CoCo part 2, from SWAMID',
      'tab'      => 'entityCategory',
      'expected' => array (
        'eduPersonPrincipalName' => self::DESC_EDUPERSONPRINCIPALNAME,
        'mail'                   => self::DESC_MAIL,
        'displayName'            => self::DESC_DISPLAYNAME,
        'cn'                     => self::DESC_CN,
        'givenName'              => self::DESC_GIVENNAME,
        'sn'                     => self::DESC_SN,
        'o'                      => self::DESC_O,
        'norEduOrgAcronym'       => self::DESC_NOREDUORGACRONYM,
        'c'                      => self::DESC_C,
        'co'                     => self::DESC_CO,
        'schacHomeOrganization'  => self::DESC_SCHACHOMEORGANIZATION,
        'subject-id'             => self::DESC_SUBJECTID,
      ),
      'nowarn'   => array (
        'persistent-id' => self::DESC_PERSISTENTID,
        'transient-id'  => self::DESC_TRANSIENTID,
      ),
      'subtest'  => 'CoCov2',
    );
    $this->tests['cocov2-3'] = array (
      'name'     => 'REFEDS CoCo, from outside SWAMID (requests civic number (personnummer) but this SHOULD NOT be released)',
      'tab'      => 'entityCategory',
      'expected' => array (
        'pairwise-id'            => self::DESC_PAIRWISEID,
        'eduPersonPrincipalName' => self::DESC_EDUPERSONPRINCIPALNAME,
        'displayName'            => self::DESC_DISPLAYNAME,
        'cn'                     => self::DESC_CN,
        'givenName'              => self::DESC_GIVENNAME,
        'schacDateOfBirth'       => self::DESC_SCHACDATEOFBIRTH,
        'sn'                     => self::DESC_SN,
        'mail'                   => self::DESC_MAIL,
      ),
      'nowarn'   => array (
        'subject-id'    => self::DESC_SUBJECTID_NOWARN,
        'persistent-id' => self::DESC_PERSISTENTID,
        'transient-id'  => self::DESC_TRANSIENTID,
      ),
      'subtest'  => 'CoCov2',
    );

    // New test for swamid
    $this->tests['esi'] = array (
      'name'     => 'SWAMID Entity Category Release Check - European Student Identifier',
      'tab'      => 'esi',
      'expected' =>array (
        'schacPersonalUniqueCode'    => 'Usually used within SWAMID for the European Student Identifier.',
        'eduPersonScopedAffiliation' => self::DESC_EDUPERSONSCOPEDAFFILIATION,
      ),
      'nowarn'   => array (
        'eduPersonAffiliation' => self::DESC_EDUPERSONAFFILIATION,
        'persistent-id'        => self::DESC_PERSISTENTID,
        'transient-id'         => self::DESC_TRANSIENTID,
      ),
      'subtest'  => 'ESI',
    );
  }
}
