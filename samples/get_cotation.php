<?php
use \Emc\Quotation;

/* Example of use for Quotation class
 * Get all available offers for your shipment
 * You can find more informations about quotation requests here: http://ecommerce.envoimoinscher.com/api/documentation/cotations/
 */
require_once('../config/autoload.php');
require_once(EMC_PARENT_DIR.'layout/header.php');


// shipper and recipient's address
$from = array(
    'country' => 'FR', // must be an ISO code, set get_country example on how to get codes
    // "state" => "", if required, state must be an ISO code as well
    'zipcode' => '75002',
    'city' => "Paris",
    'address' => '15 rue marsollier',
    'type' => 'company' // accepted values are "company" or "individual"
);

$dest =  isset($_GET['dest']) ? $_GET['dest'] : null;
switch ($dest) {
    case 'Sydney':
        $to = array(
            "country" => "AU", // must be an ISO code, set get_country example on how to get codes
            // "state" => "", if required, state must be an ISO code as well
            "zipcode" => "2000",
            "city" => "Sydney",
            "address" => "King Street",
            "type" => "individual" // accepted values are "company" or "individual"
         );
        break;
    default:
        $to = array(
            'country' => 'FR', // must be an ISO code, set get_country example on how to get codes
            // "state" => "", if required, state must be an ISO code as well
            'zipcode' => '33000',
            'city' => 'Bordeaux',
            'address' => '24, rue des Ayres',
            'type' => 'individual' // accepted values are "company" or "individual"
        );
        break;
}


/*
 * $additionalParams contains all additional parameters for your request, it includes filters or offer's options
 * A list of all possible parameters is available here: http://ecommerce.envoimoinscher.com/api/documentation/commandes/
 */
$additionalParams = array(
    'collection_date' => date("Y-m-d"),
    'delay' => 'aucun',
    'content_code' => 10120, // List of the available codes at samples/get_categories.php > List of contents
    'colis.valeur' => "42.655" // prefixed with your shipment type: "encombrant" (bulky parcel), "colis" (parcel), "palette" (pallet), "pli" (envelope)
);


/* Optionally you can define which carriers you want to quote if you don't want to quote all carriers
$additionalParams['offers'] = array(
    0 => 'MONRCpourToi',
    1 => 'SOGPRelaisColis',
    2 => 'POFRColissimoAccess',
    3 => 'CHRPChrono13',
    4 => 'UPSEExpressSaver',
    5 => 'DHLEExpressWorldwide'
);
*/
/* Parcels informations */
$parcels = array(
    'type' => 'colis', // your shipment type: "encombrant" (bulky parcel), "colis" (parcel), "palette" (pallet), "pli" (envelope)
    'dimensions' => array(
        1 => array(
            'poids' => 1, // parcel weight
            'longueur' => 15, // parcel length
            'largeur' => 16, // parcel width
            'hauteur' => 8 // parcel height
        )
    )
);

$currency = array('EUR' => '&#8364;', 'USD'=>'&#36;');

// Prepare and execute the request
$lib = new Quotation();
$lib->getQuotation($from, $to, $parcels, $additionalParams);

if (!$lib->curl_error && !$lib->resp_error) {
?>
<h3>API Quotation :</h3>
    <div class="row">
        <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th>Operator</th>
                    <th>Offers</th>
                    <th>Price</th>
                    <th>Collect</th>
                    <th>Delivery</th>
                    <th>Details</th>
                    <th>Warning</th>
                    <th>Mandatory informations</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($lib->offers as $offre) { ?>
                    <tr>
                        <td><?php echo $offre['operator']['label'];?></td>
                        <td><?php echo $offre['operator']['code'].$offre['service']['code'];?></td>
                        <td>
                        <span class="badge alert-default">
                        <?php echo $offre['price']['tax-exclusive'];?> <?php echo (isset($currency[$offre['price']['currency']]) ? $currency[$offre['price']['currency']] : $offre['price']['currency'] ) ;?></td>
                        </span>
                        <td>
                            <span class="badge alert-<?php echo $offre['collection']['type']== 'DROPOFF_POINT' ? 'info':'success'; ?>">
                            <span class="glyphicon <?php echo $offre['collection']['type']== 'DROPOFF_POINT'? 'glyphicon-map-marker':'glyphicon-home'; ?>  mr5"></span>
                                <?php echo $offre['collection']['type'];?>
                            </span>
                        </td>
                        <td>
                            <span class="badge alert-<?php echo $offre['delivery']['type']== 'PICKUP_POINT' ? 'info':'success'; ?>">
                            <span class="glyphicon <?php echo $offre['delivery']['type']== 'PICKUP_POINT'? 'glyphicon-map-marker':'glyphicon-home'; ?>  mr5"></span>
                                <?php echo $offre['delivery']['type'];?>
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-xs btn-default" data-container="body" data-toggle="popover" data-placement="left" data-content="<?php echo str_replace('"', "'", implode('<br /> - ', $offre['characteristics'])); ?>">
                                <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                Details
                            </button>

                        </td>
                        <td>
                            <button type="button" class="btn btn-xs btn-warning" data-container="body" data-toggle="popover" data-placement="left" data-content="<?php echo $offre['alert']; ?>">
                                <span class="glyphicon glyphicon-warning-sign"></span>
                                Warning
                            </button>

                        </td>
                        <td>
                            <button type="button" class="btn btn-xs btn-danger" data-container="body" data-toggle="popover" data-placement="left" data-content="<?php echo '- '. str_replace('"', "'", implode('<br /> - ', array_keys($offre['mandatory']))); ?>">
                                <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
                                Mandatory informations
                            </button>
                        </td>
                    </tr>
<?php
            }
?>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo '<div class="alert alert-danger">';
    handle_errors($lib);
    echo'</div>';
}
require_once(EMC_PARENT_DIR.'layout/quotation_datails.php');
?>
<div class="well well-sm">
    <button type="button" class="btn btn-xs btn-default" id="toogleDebug">
        Toggle Debug
    </button>
    <pre id="debug" style="display: none">
        <?php print_r(array_merge($lib->getApiParam(), array('API response :' =>$lib->offers))); ?>
    </pre>
</div>
<?php
require_once(EMC_PARENT_DIR.'layout/footer.php');
