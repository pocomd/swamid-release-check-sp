# SWAMID release-check

This is a tool checking the response from an IdP.
- Show what an IdP sends
- Verify that en IdP sends correct attributes for sertan EntityCaterorys
- Verify MFA suppport in an IdP

## Requirements

- Webserver with Shibboleth. We use docker image created from https://github.com/SUNET/docker-swamid-metadata-sp.
- MySQL/MariaDB database. Local or on a remote server/cluster.
- composer

# Setup

## attribute-map.xml for Shibboleth SP

The code require some minor changes to this file to work.

All Attribute id needs to start with **saml_** to be able to filter them.
One way to solve this is to run the following sed command
```bash
sed 's/\(<Attribute.*\) id="/\1 id="saml_/' attribute-map.xml
```

We also need to add the following to be able to show / verify:
```xml
<!-- for EC / Assurance checks -->
<Attribute name="urn:oasis:names:tc:SAML:attribute:assurance-certification" id="Assurance-Certification"/>
<Attribute name="http://macedir.org/entity-category" id="Entity-Category"/>
<Attribute name="http://macedir.org/entity-category-support" id="Entity-Category-Support"/>
```

You might also need to remove comments around parts of Attribute:s your users might want to test, and add extra attributes used in your environment.

### rename some attributes ?

Some attributes in standrad Shibboleth differ from what they are called in schema. You might want to rename those ?

| OID                               | From                 | To                         |
|-----------------------------------|----------------------|----------------------------|
| urn:oid:1.3.6.1.4.1.5923.1.1.1.6  | eppn                 | eduPersonPrincipalName     |
| urn:oid:1.3.6.1.4.1.5923.1.1.1.1  | unscoped-affiliation | eduPersonAffiliation       |
| urn:oid:1.3.6.1.4.1.5923.1.1.1.9  | affiliation          | eduPersonScopedAffiliation |
| urn:oid:1.3.6.1.4.1.5923.1.1.1.11 | assurance            | eduPersonAssurance         |
| urn:oid:1.3.6.1.4.1.5923.1.1.1.10 | persistent-id        | eduPersonTargetedID        |

### norEduPerson schema

People in the Nordic Countries might want to add this set of attributes :
```xml
<!-- norEduPerson attributes... -->
<Attribute name="urn:oid:1.3.6.1.4.1.2428.90.1.10" id="saml_norEduPersonLegalName"/>
<Attribute name="urn:oid:1.3.6.1.4.1.2428.90.1.5" id="saml_norEduPersonNIN"/>
<Attribute name="urn:oid:1.3.6.1.4.1.2428.90.1.4" id="saml_norEduPersonLIN"/>
<Attribute name="urn:oid:1.3.6.1.4.1.2428.90.1.6" id="saml_norEduOrgAcronym"/>
<Attribute name="urn:oid:1.3.6.1.4.1.2428.90.1.3" id="saml_norEduPersonBirthDate"/>
<Attribute name="urn:oid:1.3.6.1.4.1.2428.90.1.7" id="saml_norEduOrgUniqueIdentifier"/>
<Attribute name="urn:oid:1.3.6.1.4.1.2428.90.1.8" id="saml_norEduOrgUnitUniqueIdentifier"/>
<Attribute name="urn:oid:1.3.6.1.4.1.2428.90.1.12" id="saml_norEduOrgNIN"/>
<Attribute name="urn:oid:1.3.6.1.4.1.2428.90.1.1" id="saml_norEduOrgUniqueNumber"/>
<Attribute name="urn:oid:1.3.6.1.4.1.2428.90.1.2" id="saml_norEduOrgUnitUniqueNumber"/>
```

## shibboleth2.xml

We also need to update shibboleth2.xml.

Use or own error page for better information controll.
```xml
<Errors ...
  redirectErrors="https://sp.example.org/error.php"
.../>
```

Prefix extracted info from Metadata with *Meta-*

```xml
<ApplicationDefaults ...
  metadataAttributePrefix="Meta-"
...>
```

And add what we want to extract
```xml
<AttributeExtractor type="Metadata" errorURL="errorURL" DisplayName="displayName"
  InformationURL="informationURL" PrivacyStatementURL="privacyStatementURL"
  registrationAuthority="registrationAuthority" OrganizationURL="organizationURL">
  <ContactPerson id="Support-Administrative"  contactType="administrative" formatter="$EmailAddress" />
  <ContactPerson id="Support-Contact"  contactType="support" formatter="$EmailAddress" />
  <ContactPerson id="Support-Technical"  contactType="technical" formatter="$EmailAddress" />
  <ContactPerson id="Other-Contact"  contactType="other" formatter="$EmailAddress" />
  <Logo id="Logo" height="256" width="256" formatter="<img src='$_string' height='$height' width='$width'/>"/>
</AttributeExtractor>
```