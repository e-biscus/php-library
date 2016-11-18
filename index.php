<?php
require_once('config/autoload.php');
require_once(EMC_PARENT_DIR.'layout/header.php');
?>
<div>
    <h3>Quick start / Boxtal PHP Library</h3>
    <p>This PHP library aims to present the PHP implementation of the <a href="https://www.envoimoinscher.com" target="_blank">EnvoiMoinsCher.com</a> API.</p>
    <p>We will see step by step the essential blocks for building a custom shipping module on your e-shop:/p>
    <ul class="myTab">
        <li><a href="#signup" role="tab" data-toggle="tab">Signup</a></li>
        <li><a href="#cotations" role="tab" data-toggle="tab">Get quotations</a></li>
        <li><a href="#order" role="tab" data-toggle="tab">Make Orders</a></li>
        <li><a href="#cat" role="tab" data-toggle="tab">Available content types</a></li>
        <li><a href="#country" role="tab" data-toggle="tab">Countries list</a></li>
    </ul> 
     <p>For more information on input parameters, classes, changelog, please refer to our <a href="http://ecommerce.envoimoinscher.com/api/documentation/" target="_blank">documentation</a> (in french).</p>
    <br/>
     <h4>Installation</h4>
        To install Boxtal PHP Library, simply : <br/>
        <b>$ composer require boxtale/php-library </b>
        <br/><br/>
        
     <h4>Requirements et and general information about the EnvoiMoinsCher API</h4>
     <p>In order to use the API, you need to create a (free) user account using the API (postUserSignup) or on <a href="http://www.envoimoinscher.com/inscription.html" target="_blank">www.envoimoinscher.com</a>, check the "I would like to install the EnvoiMoinsCher module directly on my E-commerce website." box. You will then receive an email with your API keys and be able to start your tests.</p>
    Make sure to fill in your credentials in the configuration file : config/config.php
    <pre>
    /* To use 'test' or 'prod' environment  */
    define("EMC_MODE", "test");

    if (EMC_MODE == "prod") {
        /* To set 'prod' environment constants */
        define("EMC_USER", "yourLoginProd");
        define("EMC_PASS", "yourPasswordProd");
        define("EMC_KEY", "yourApiKeyProd");
    } else {
        /* To set 'test' environment constants */
        define("EMC_USER", "yourLoginTest");
        define("EMC_PASS", "yourPasswordTest");
        define("EMC_KEY", "yourApiKeyTest");
    }</pre>
     
    <br/>
    <h4>Library content</h4>
    <p>The package contains 5 main directories:</p>
    <ul>
        <li>ca - contains the certificate required for communication with the API</li>
        <li>Emc - contains classes that allow interaction with the API</li>
        <li>config - contains the config and autoload files</li>
        <li>samples - contains files with examples of using the library</li>
        <li>test - a file that tests whether your development environment has all the extensions used by the library</li>
    </ul>

    <br/>

<ul class="myTab nav nav-tabs" role="tablist">
    <li class="active"><a href="#signup" role="tab" data-toggle="tab">Signup</a></li>
    <li><a href="#cotations" role="tab" data-toggle="tab">Get quotations</a></li>
    <li><a href="#order" role="tab" data-toggle="tab">Make Orders</a></li>
    <li><a href="#cat" role="tab" data-toggle="tab">Available content types</a></li>
    <li><a href="#country" role="tab" data-toggle="tab">Countries list</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="signup">
        <h5 id="orders">1. Signup to envoimoincher.com </h5>
        <p>To create a (free) Boxtal user account, you have two options:</p>
        <ul>
            <li>Either on <a href="http://www.envoimoinscher.com/inscription.html" target="_blank">www.envoimoinscher.com</a>, check the "I would like to install the EnvoiMoinsCher module directly on my E-commerce website." box.</li>
            <li>Or using the postUserSignup method available in EnvoiMoinsCher API </li>
        </ul>
        <p>In both cases, you will receive an email confirming that your account was successfully created and 24h later another email with your API keys.</p>
        <pre>
    require __DIR__ . '/vendor/autoload.php';

    // Params to create account as Professional
    $params =array(
        'facturation.contact_ste'=>'Boxtal',
        'facturation.contact_civ'=>'M.', // Accepted values are "M" (sir) or "Mme" (madam)
        'facturation.contact_nom'=>'Snow',
        'facturation.contact_prenom'=>'Jon',
        'facturation.adresse1'=>'15 rue Marsollier',
        'facturation.adresse2'=>'', // Address line 2
        'facturation.adresse3'=>'', // Address line 3
        'facturation.ville'=>'Paris', // City
        'facturation.pays_iso'=>'FR', // Country ISO code
        'facturation.codepostal'=>'75001',
        'facturation.contact_email'=>'jsnow@boxtal.com',
        'facturation.contact_tel'=>'0606060606',
        'facturation.contact_locale'=>'fr_FR',
        'facturation.defaut_enl'=>'on', // Set the adress as default collect adress
        'facturation.contact_stesiret'=>'12345678912345', // SIRET
        'facturation.contact_tvaintra'=>'123456', // Intra-community VAT No

        'moduleEMC'=>'on', // To obtain an API key within 24 hours.
        'user.login'=>'jsnow',
        'user.password'=> urlencode($lib->encryptPassword('password')),

        //Optional
        'user.profession'=>'gerant', // Your title, (gerant, developpeur, agence, free-lance, autre)
        'user.partner_code'=>'', // If you have a partner code
        'user.volumetrie'=>'2', // Your average shipping quantity peer month? 1 (less than 10), 2 (10 to 100), 3 (100 to 250), 4 (250 to 500), 5 (500 to 1000), 6 (1000 to 2000), 7 (2000 to 5000), 8 (5000 to 10000)
        'user.site_online'=>'1', // Is your website online ? (1 (yes), 0 (no))
        'user.logiciel'=>'prestashop-1.6' // Possible values (prestashop-1.5, prestashop-1.6, drupal, magento, woocommerce, oscommerce, oxatis)
    );
    $lib = new \Emc\User();
    
    // Not setting credentials to empty would result in creating a linked account to the parent credentials
    $lib->setLogin('');
    $lib->setPassword('');
    $lib->setKey('');

    // Setting environment to 'prod' will create a valid account with test and production API keys
    // Creating an account in a 'test' environment would result in an incomplete account
    $lib->setEnv('prod');

    $response = $lib->postUserSignup($params);</pre>
       <p>See also: <a href="<?php echo EMC_PARENT_DIR; ?>samples/post_signup.php">User Signup</a>
    </div>
     <div class="tab-pane" id="cotations">
        <h5 id="quotations">2. How to get a quotation ?</h5>
        <p>Here are the elements needed to get a quotation:</p>
        <ul>
            <li>your shipment type: "encombrant" (bulky parcel), "colis" (parcel), "palette" (pallet), "pli" (envelope)</li>
            <li>your content type id</li>
            <li>the sender's country, city, address and type (company or individual)</li>
            <li>the recipient's country, city and type (company or individual)</li>
            <li>the collection date (sundays and holidays excluded)</li>
            <li>your shipment content value (for a cross-boarder quotation)</li>
        </ul>
        <pre>
    require __DIR__ . '/vendor/autoload.php';

    // shipper address
    $from = array(
        'country' => 'FR', // must be an ISO code, set get_country example on how to get codes
        'zipcode' => '38400',
        'city' => "Saint Martin d'Hères",
        'address' => '13 Rue Martin Luther King',
        'type' => 'company', // accepted values are "company" or "individual"
    );
    // recipient's address
    $to = array(
        'country' => 'FR', // must be an ISO code, set get_country example on how to get codes
        'zipcode' => '33000',
        'city' => 'Bordeaux',
        'address' => '24, rue des Ayres',
        'type' => 'individual', // accepted values are "company" or "individual"
    );


    /* Parcels informations */
    $parcels = array(
        'type' => 'colis', // your shipment type: "encombrant" (bulky parcel), "colis" (parcel), "palette" (pallet), "pli" (envelope)
        'dimensions' => array(
            1 => array(
                'poids' => 1,
                'longueur' => 15,
                'largeur' => 16,
                'hauteur' => 8
            )
        )
    );

    $additionalParams = array(
        'collection_date' => date("Y-m-d"),
        'delay' => 'aucun',
        'content_code' => 10120, // List of the available codes at samples/get_categories.php > List of contents
        'colis.valeur' => "42.655" // prefixed with your shipment type: "encombrant" (bulky parcel), "colis" (parcel), "palette" (pallet), "pli" (envelope)
    );

    $lib = new \Emc\Quotation();
    $lib->getQuotation($from, $to, $parcels, $additionalParams);
    // The offers list is available on the array : $lib->offers

    if (!$lib->curl_error && !$lib->resp_error) {
        print_r($lib->offers);
    } else {
        handle_errors($lib);
    }</pre>
        <p>See also: <a href="<?php echo EMC_PARENT_DIR; ?>samples/get_cotation.php">Paris to Bordeaux</a>
        <p>See also: <a href="<?php echo EMC_PARENT_DIR; ?>samples/get_cotation.php?dest=Sydney">Paris to Sydney (international)</a>

        <br/>

    </div>
    <div class="tab-pane" id="order">
        <h5 id="orders">3. How to make an order ?</h5>
        <p>The process of making an order is the same as making a quotation. The only difference is the extra parameters you need to send.<br/>
        For the sender and the recipient, you need to give phone numbers, name and first name.<br/>
        For the shipment, depending on the carrier chosen,
        you might need to give hours for pickup availability, dropoff and/or pickup parcel points.</p>
        <p>All international shipments need an <em>object.</em>valeur parameter (where <em>object</em> is the shipment type: "encombrant" (bulky parcel), "colis" (parcel), "palette" (pallet), "pli" (envelope)).</p>
        <pre>
    require __DIR__ . '/vendor/autoload.php';

    // shipper address
    $from = array(
        'country' => 'FR',  // must be an ISO code, set get_country example on how to get codes
        'zipcode' => '75002',
        'city' => 'Paris',
        'address' => '15, rue Marsollier',
        'type' => 'company', // accepted values are "company" or "individual"
        'title' => 'M', // accepted values are "M" (sir) or "Mme" (madam)
        'firstname' => 'Jon',
        'lastname' => 'Snow',
        'societe' => 'Boxtal', // company name
        'email' => 'jsnow@boxtal.com',
        'phone' => '0606060606',
        'infos' => 'Some informations about this address'
    );


    // Recipient's address
    $to = array(
        'country' => 'FR',  // must be an ISO code, set get_country example on how to get codes
        'zipcode' => '13002',
        'city' => 'Marseille',
        'address' => '1, rue Chape',
        'type' => 'individual', // accepted values are "company" or "individual"
        'title' => 'Mme', // accepted values are "M" (sir) or "Mme" (madam)
        'firstname' => 'Jane',
        'lastname' => 'Doe',
        'email' => 'jdoe@boxtal.com',
        'phone' => '0606060606',
        'infos' => 'Some informations about this address'
    );

    /* Parcels informations */
    $parcels = array(
        'type' => 'colis', // your shipment type: "encombrant" (bulky parcel), "colis" (parcel), "palette" (pallet), "pli" (envelope)
        'dimensions' => array(
            1 => array(
                'poids' => 5, // parcel weight
                'longueur' => 15, // parcel length
                'largeur' => 16, // parcel width
                'hauteur' => 8 // parcel height
            )
        )
    );

    $additionalParams = array(
        'collection_date' => date('Y-m-d'),
        'delay' => "aucun", // no delay, meaning shipping as soon as possible
        'assurance.selection' => false, // whether you want an extra insurance or not
        'url_push' => 'www.my-website.com/push.php&order=',
        'content_code' => 40110,
        'colis.description' => "Tissus, vêtements neufs", // prefixed with your shipment type: "encombrant" (bulky parcel), "colis" (parcel), "palette" (pallet), "pli" (envelope)
        'colis.valeur' => "42.655", // prefixed with your shipment type: "encombrant" (bulky parcel), "colis" (parcel), "palette" (pallet), "pli" (envelope)
        'depot.pointrelais' => 'CHRP-POST',
        'operator' => 'CHRP',
        'service' => 'Chrono18'
    );


    // Prepare and execute the request
    $lib = new \emc\Quotation();

    $orderPassed = $lib->makeOrder($from, $to, $parcels, $additionalParams);

    if (!$lib->curl_error && !$lib->resp_error) {
        print_r($lib->order);
    } else {
        handle_errors($lib);
    }</pre>
    </div>
    <div class="tab-pane" id="cat">
        <h5 id="categories">4. How can I get a list of available content types ?</h5>
        <p>Using the API, you can get a list of the available content types which you will be able to use in your module.
            The "content type" is the nature of the content that you are shipping.</p>
        <pre>
    require __DIR__ . '/vendor/autoload.php';

    $lib = new \Emc\ContentCategory();
    $lib->getCategories(); // load all content categories
    $lib->getContents();   // load all content types

    // The content categories list is available on the array : $lib->categories
    // The content types list is available on the array : $lib->contents

    if (!$lib->curl_error && !$lib->resp_error) {
        print_r($lib->categories);
        print_r($lib->contents);

    } else {
        handle_errors($lib);
    }</pre>
        <p>The API will need the content type ids as a parameter for quotations and orders.</p>
        <p>See also: <a href="<?php echo EMC_PARENT_DIR; ?>samples/get_categories.php">list of contents example</a></p>
        <br/>
    </div>
    <div class="tab-pane" id="country">
        <h5 id="countries">5. How can I get a list of countries ?</h5>
        <p>Orders shipping with the EnvoiMoinsCher API use country ISO codes. For now, the system only allows shipments from France to abroad, not from abroad to France. Here is how to get the list of countries:</p>
        <pre>

    $lib = new \Emc\Country();
    $lib->getCountries();
    // The countries list is available on the array : $lib->countries

    if (!$lib->curl_error && !$lib->resp_error) {
        print_r($lib->countries);
    } else {
        handle_errors($lib);
    }</pre>
        <p>The API will need the country ISO code as a parameter for several actions.</p>
        <p>See also: <a href="<?php echo EMC_PARENT_DIR; ?>samples/get_country.php">list of countries example</a></p>
        <br/>
    </div>
    <br/>
</div>

<h4>Useful functions</h4>
    <p>Once you've created a library instance, you can use the following functions.</p>

    <pre>
    /* To change login for this request only */
    $lib->setLogin("otherLogin");
    
    /* To change password for this request only */
    $lib->setPassword("otherPassword");
    
    /* To change API key for this request only */
    $lib->setKey("otherKey");
    
    /* To change environment for this request only */
    $lib->setEnv("prod");
    
    /* To set API return language */
    $lib->setLocale("fr-FR");</pre>
<br/>
<p>For more information on input parameters, classes, changelog, please refer to our <a href="http://ecommerce.envoimoinscher.com/api/documentation/" target="_blank">documentation</a> (in french).</p>
<p>If you have any trouble implementing the library, email us at <a href="mailto:api@envoimoinscher.com">api@envoimoinscher.com</a>.</p>
</div>
<div class="footer">
        <p>&copy; Boxtal 2016</p>
      </div>
<script>
  $(document).ready(function() {
    $('myTab a:first').tab('show')
    });
</script>

<?php
require_once(EMC_PARENT_DIR.'layout/footer.php');
