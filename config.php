<?php
/**
 *
 * Configuration variables for your NetBeez scheduled test report generator
 *
 */

//Optional error display for debugging (set to false for production)
ini_set( "display_errors", false);

//The host address of the NetBeez API (this is usually your NB dashboard's hostname)
define("API_HOST", "https://cloud-demo.netbeez.net");

//The NetBeez API version
define("API_VERSION", "v1");

//Your authentication key for accessing the API
define("API_AUTH_KEY", "680a3d759573306f4bd09e88999c348ea42ae60c");

//Boolean setting for cURL option to verify the SSL host
define("SSL_VERIFY_HOST", false);

//Boolean setting for cURL option to verify the SSL peer.
//Default is false due to issue with certificate configuration on internal NetBeez instances
define("SSL_VERIFY_PEER", false);