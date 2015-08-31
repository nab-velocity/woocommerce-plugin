<?php
/*
    Plugin Name: Velocity
    Plugin URI: http://nabvelocity.com
    Description: Velocity Payment Gateway for WooCommerce. Redefining Payments, Simplifying Lives! Empowering any business to collect money online within minutes that helps you sell anything. Beautifully.
    Version: 1.0.0
    Author: chetu
    Author URI: http://www.chetu.com/
*/

// Hook for payment gateway class.
add_action('plugins_loaded', 'woocommerce_velocity_init', 0);
define('velocity_imgdir', WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/assets/img/');

/* 
 * payment gateway method call from above hooks.
 */
function woocommerce_velocity_init() {
    if(!class_exists('WC_Payment_Gateway')) return; // check WC_Payment_Gateway class exist on plugin page or not.

    /**
     * Gateway class to intract with velocity gateway.
     */
    class WC_Velocity extends WC_Payment_Gateway {

        public function __construct() {

            $this->id 		      = 'velocity';
            $this->method_title       = 'Velocity';
            $this->method_description = "Redefining Payments, Simplifying Lives";
            $this->has_fields 	      = true;
            $this->icon 	      = velocity_imgdir . 'logo.png';		
            
            $this->init_form_fields();
            $this->init_settings();
            $this->title 	      = $this->settings['title'];
            
            if ($this->settings['testmode'] == "yes") { // work for test mode.
                $this->identitytoken        = "PHNhbWw6QXNzZXJ0aW9uIE1ham9yVmVyc2lvbj0iMSIgTWlub3JWZXJzaW9uPSIxIiBBc3NlcnRpb25JRD0iXzdlMDhiNzdjLTUzZWEtNDEwZC1hNmJiLTAyYjJmMTAzMzEwYyIgSXNzdWVyPSJJcGNBdXRoZW50aWNhdGlvbiIgSXNzdWVJbnN0YW50PSIyMDE0LTEwLTEwVDIwOjM2OjE4LjM3OVoiIHhtbG5zOnNhbWw9InVybjpvYXNpczpuYW1lczp0YzpTQU1MOjEuMDphc3NlcnRpb24iPjxzYW1sOkNvbmRpdGlvbnMgTm90QmVmb3JlPSIyMDE0LTEwLTEwVDIwOjM2OjE4LjM3OVoiIE5vdE9uT3JBZnRlcj0iMjA0NC0xMC0xMFQyMDozNjoxOC4zNzlaIj48L3NhbWw6Q29uZGl0aW9ucz48c2FtbDpBZHZpY2U+PC9zYW1sOkFkdmljZT48c2FtbDpBdHRyaWJ1dGVTdGF0ZW1lbnQ+PHNhbWw6U3ViamVjdD48c2FtbDpOYW1lSWRlbnRpZmllcj5GRjNCQjZEQzU4MzAwMDAxPC9zYW1sOk5hbWVJZGVudGlmaWVyPjwvc2FtbDpTdWJqZWN0PjxzYW1sOkF0dHJpYnV0ZSBBdHRyaWJ1dGVOYW1lPSJTQUsiIEF0dHJpYnV0ZU5hbWVzcGFjZT0iaHR0cDovL3NjaGVtYXMuaXBjb21tZXJjZS5jb20vSWRlbnRpdHkiPjxzYW1sOkF0dHJpYnV0ZVZhbHVlPkZGM0JCNkRDNTgzMDAwMDE8L3NhbWw6QXR0cmlidXRlVmFsdWU+PC9zYW1sOkF0dHJpYnV0ZT48c2FtbDpBdHRyaWJ1dGUgQXR0cmlidXRlTmFtZT0iU2VyaWFsIiBBdHRyaWJ1dGVOYW1lc3BhY2U9Imh0dHA6Ly9zY2hlbWFzLmlwY29tbWVyY2UuY29tL0lkZW50aXR5Ij48c2FtbDpBdHRyaWJ1dGVWYWx1ZT5iMTVlMTA4MS00ZGY2LTQwMTYtODM3Mi02NzhkYzdmZDQzNTc8L3NhbWw6QXR0cmlidXRlVmFsdWU+PC9zYW1sOkF0dHJpYnV0ZT48c2FtbDpBdHRyaWJ1dGUgQXR0cmlidXRlTmFtZT0ibmFtZSIgQXR0cmlidXRlTmFtZXNwYWNlPSJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcyI+PHNhbWw6QXR0cmlidXRlVmFsdWU+RkYzQkI2REM1ODMwMDAwMTwvc2FtbDpBdHRyaWJ1dGVWYWx1ZT48L3NhbWw6QXR0cmlidXRlPjwvc2FtbDpBdHRyaWJ1dGVTdGF0ZW1lbnQ+PFNpZ25hdHVyZSB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC8wOS94bWxkc2lnIyI+PFNpZ25lZEluZm8+PENhbm9uaWNhbGl6YXRpb25NZXRob2QgQWxnb3JpdGhtPSJodHRwOi8vd3d3LnczLm9yZy8yMDAxLzEwL3htbC1leGMtYzE0biMiPjwvQ2Fub25pY2FsaXphdGlvbk1ldGhvZD48U2lnbmF0dXJlTWV0aG9kIEFsZ29yaXRobT0iaHR0cDovL3d3dy53My5vcmcvMjAwMC8wOS94bWxkc2lnI3JzYS1zaGExIj48L1NpZ25hdHVyZU1ldGhvZD48UmVmZXJlbmNlIFVSST0iI183ZTA4Yjc3Yy01M2VhLTQxMGQtYTZiYi0wMmIyZjEwMzMxMGMiPjxUcmFuc2Zvcm1zPjxUcmFuc2Zvcm0gQWxnb3JpdGhtPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwLzA5L3htbGRzaWcjZW52ZWxvcGVkLXNpZ25hdHVyZSI+PC9UcmFuc2Zvcm0+PFRyYW5zZm9ybSBBbGdvcml0aG09Imh0dHA6Ly93d3cudzMub3JnLzIwMDEvMTAveG1sLWV4Yy1jMTRuIyI+PC9UcmFuc2Zvcm0+PC9UcmFuc2Zvcm1zPjxEaWdlc3RNZXRob2QgQWxnb3JpdGhtPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwLzA5L3htbGRzaWcjc2hhMSI+PC9EaWdlc3RNZXRob2Q+PERpZ2VzdFZhbHVlPnl3NVZxWHlUTUh5NUNjdmRXN01TV2RhMDZMTT08L0RpZ2VzdFZhbHVlPjwvUmVmZXJlbmNlPjwvU2lnbmVkSW5mbz48U2lnbmF0dXJlVmFsdWU+WG9ZcURQaUorYy9IMlRFRjNQMWpQdVBUZ0VDVHp1cFVlRXpESERwMlE2ZW92T2lhN0pkVjI1bzZjTk1vczBTTzRISStSUGRUR3hJUW9xa0paeEtoTzZHcWZ2WHFDa2NNb2JCemxYbW83NUFSWU5jMHdlZ1hiQUVVQVFCcVNmeGwxc3huSlc1ZHZjclpuUytkSThoc2lZZW4vT0VTOUdtZUpsZVd1WUR4U0xmQjZJZnd6dk5LQ0xlS0FXenBkTk9NYmpQTjJyNUJWQUhQZEJ6WmtiSGZwdUlablp1Q2l5OENvaEo1bHU3WGZDbXpHdW96VDVqVE0wU3F6bHlzeUpWWVNSbVFUQW5WMVVGMGovbEx6SU14MVJmdWltWHNXaVk4c2RvQ2IrZXpBcVJnbk5EVSs3NlVYOEZFSEN3Q2c5a0tLSzQwMXdYNXpLd2FPRGJJUFpEYitBPT08L1NpZ25hdHVyZVZhbHVlPjxLZXlJbmZvPjxvOlNlY3VyaXR5VG9rZW5SZWZlcmVuY2UgeG1sbnM6bz0iaHR0cDovL2RvY3Mub2FzaXMtb3Blbi5vcmcvd3NzLzIwMDQvMDEvb2FzaXMtMjAwNDAxLXdzcy13c3NlY3VyaXR5LXNlY2V4dC0xLjAueHNkIj48bzpLZXlJZGVudGlmaWVyIFZhbHVlVHlwZT0iaHR0cDovL2RvY3Mub2FzaXMtb3Blbi5vcmcvd3NzL29hc2lzLXdzcy1zb2FwLW1lc3NhZ2Utc2VjdXJpdHktMS4xI1RodW1icHJpbnRTSEExIj5ZREJlRFNGM0Z4R2dmd3pSLzBwck11OTZoQ2M9PC9vOktleUlkZW50aWZpZXI+PC9vOlNlY3VyaXR5VG9rZW5SZWZlcmVuY2U+PC9LZXlJbmZvPjwvU2lnbmF0dXJlPjwvc2FtbDpBc3NlcnRpb24+";
                $this->applicationprofileid = "14644";
                $this->merchantprofileid    = "PrestaShop Global HC"; // merchant profile id default provided by velocity
                $this->workflowid 	    = "2317000001";   // workflowid / serviceid default provided by velocity
                $this->isTestAccount 	    = true;            // this for test url.
                $this->description 	    = $this->settings['description']; // description about payment gateway. 
            } else {
                $this->identitytoken        = $this->settings['identitytoken'];
                $this->workflowid 	    = $this->settings['workflowid']; // workflowid / serviceid default unique provided by velocity
                $this->applicationprofileid = $this->settings['applicationprofileid'];
                $this->merchantprofileid    = $this->settings['merchantprofileId']; // merchant profile id unique provided by velocity
                $this->isTestAccount 	    = false;
                $this->description 	    = $this->settings['description'];
            }					

            if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
                    /* 2.0.0 */
                    add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options'));
            } else {
                    /* 1.6.6 */
                    add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
            }
            
            if(isset($_POST['order_id'])) // check order_id is set or not.
                    $obj = new WC_Order_Refund($_POST['order_id']);  /* Refund payment process to send request on gateway */

            if (isset($obj->id) && isset($obj->order_type) && $obj->order_type == 'refund' && isset($obj->post)) {

                global $wpdb;
                $table_post = $wpdb->prefix . 'posts';
                    $row = $wpdb->get_results( "SELECT ID FROM $table_post where post_parent = ".$obj->id );
                    $refund_id = end($row)->ID;
                    $table_post_meta = $wpdb->prefix . 'postmeta';
                    $refund_row = $wpdb->get_results( "SELECT meta_value FROM $table_post_meta where meta_key = '_refund_amount' and post_id = $refund_id" );
                    $refund_ammount = $refund_row[0]->meta_value;
                    $velocity_transaction_table = $wpdb->prefix . 'velocity_transaction';
                    $transaction_id_row = $wpdb->get_results( "SELECT transaction_id FROM $velocity_transaction_table where order_id = $obj->id" );
                    $transaction_id = $transaction_id_row[0]->transaction_id;
                    if( isset($refund_ammount) && isset($transaction_id) ) {
                            $this->refund_payment($refund_ammount, $transaction_id, $obj->id );
                    }
            }
        }  
        
        /* 
         * This method call if admin enter ammount manual and request for payment.
         * @param float $refund_ammount request ammount for refund.
         * @param string $transaction_id transactionid is authorizeandcapture transaction for same order.
         * @param int $order_id is corresponding order number on the behalf of transaction done.
         * @global object $wpdb is woocommerce global object.
         */
        function refund_payment($refund_ammount, $transaction_id, $order_id) {

            // include the PHP SDK.
            require_once 'sdk/Velocity.php';

            // create SDK object to call the all SDK methods and genrate the sessiontoken.
            try {
                $obj_transaction = new VelocityProcessor($this->applicationprofileid, $this->merchantprofileid, $this->workflowid, $this->isTestAccount, $this->identitytoken, null);
            } catch(Exception $e) {
                print_r($e->getMessage());
                return;
            }

            try {

                // returnbyid method call for payment refund via transaction_id. 
                $res_returnbyid = $obj_transaction->returnById( array(
                                                                        'amount' => $refund_ammount, 
                                                                        'TransactionId' => $transaction_id
                                                                        ) 
                                                              );

                try {
                    $xml = VelocityXmlCreator::returnByIdXML(number_format($refund_ammount, 2, '.', ''), $transaction_id);  // got ReturnById xml object.  

                    $req = $xml->saveXML();
                    $obj_req = serialize($req);

                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }

                global $wpdb;
                $velocity_transaction_table = $wpdb->prefix . 'velocity_transaction'; // table name which is use to save the transaction data. 


                if (is_array($res_returnbyid) && isset($res_returnbyid['StatusCode']) && $res_returnbyid['StatusCode'] == '000') { // check the transaction success or failure.

                    $transaction_id     = $res_returnbyid['TransactionId'];
                    $transaction_status = $res_returnbyid['TransactionState'];
                    $order_num          = $res_returnbyid['OrderId'];
                    $obj_res            = serialize($res_returnbyid);
                    $order              = new WC_Order($order_id);
                    $currency           = $order->get_order_currency();
                    
                    $order->add_order_note('Your payment refunded successful.<br/>Refunded amount is '.$refund_ammount.' '.$currency, 1);
                    $order->update_status('Refunded');

                    $wpdb->insert( 
                        $velocity_transaction_table, 
                        array( 
                                'id'                 => '', 
                                'transaction_id'     => $transaction_id,
                                'transaction_status' => $transaction_status,
                                'order_id'           => $order_num,
                                'request_obj'        => $obj_req,
                                'response_obj'       => $obj_res
                        ), 
                        array( 
                                '%d', 
                                '%s',
                                '%s',
                                '%s',
                                '%s',
                                '%s'		
                        ) 
                    );

                } else if (is_array($res_returnbyid) && isset($res_returnbyid['StatusCode']) && $res_authandcap['StatusCode'] != '000') {
                    throw new Exception($res_returnbyid['StatusMessage']);
                } else if (is_string($res_returnbyid)) {
                    throw new Exception($res_returnbyid);
                } else {
                    throw new Exception('Unkown Error occurs please contact the site admin or technical team.');
                }

            } catch(Exception $ex) {
                print_r($ex->getMessage());
            }

        }

        /* 
         *	admin form and other option title description define from here some are not editable from admin side some like workflowid merchantprofile id are editable from admin *    panel.
         */
        function init_form_fields(){
            $this->form_fields = array(
                'enabled' => array(
                                'title'       => __('Enable/Disable', 'nab'),
                                'type' 	      => 'checkbox',
                                'label'       => __('Enable Velocity Payment Module.', 'nab'),
                                'default'     => 'no',
                                'description' => 'Show in the Payment List as a payment option'
                        ),
                'title' => array(
                                'title'       => __('', 'nab'),
                                'type'	      => 'hidden',
                                'default'     => __('Credit Card Payments', 'nab'),
                                'desc_tip'    => true
                        ),
                'description' => array(
                                'title'       => __('', 'nab'),
                                'type' 	      => 'hidden',
                                'default'     => __('Pay securely by Credit through Velocity Secure Servers.', 'nab'),
                                'desc_tip'    => true
                        ),
                'identitytoken' => array(
                                'title'       => __('Identity Token', 'nab'),
                                'type' 	      => 'textarea',
                                'description' => __('Given to Merchant by Velocity'),
                                'desc_tip'    => true
                        ),
                'workflowid' => array(
                                'title'       => __('WorkFlowId/ServiceId', 'nab'),
                                'type' 	      => 'text',
                                'description' => __('Given to Merchant by Velocity'),
                                'desc_tip'    => true
                        ),
                'applicationprofileid' => array(
                                'title'       => __('ApplicationProfileId', 'nab'),
                                'type' 	      => 'text',
                                'description' => __('Given to Merchant by Velocity'),
                                'desc_tip'    => true
                        ),
                'merchantprofileId' => array(
                                'title'       => __('MerchantProfileId', 'nab'),
                                'type' 	      => 'text',
                                'description' => __('Given to Merchant by Velocity', 'nab'),
                                'desc_tip'    => true
                ),
                'testmode' => array(
                                'title'       => __('TEST Mode', 'nab'),
                                'type' 	      => 'checkbox',
                                'label'       => __('Enable Velocity TEST Transactions.', 'nab'),
                                'default'     => 'yes',
                                'description' => __('Tick to run TEST Transaction on the Velocity platform'),
                                'desc_tip'    => true
                )
            );
        }

        /**
         * Admin Panel Options
         * - configure the velocity payment gateway according to our need and save velocity credentials.
         **/
        public function admin_options(){
            echo '<h3>'.__('Velocity', 'nab').'</h3>';
            echo '<p>'.__('Redefining Payments, Simplifying Lives! Empowering any business to collect money online within minutes').'</p>';
            echo '<table class="form-table">';
            // Generate the HTML For the settings form.
            $this->generate_settings_html();
            echo '</table>';
        }

        /**
         *  velocity payment form field show directly on check out page
                 *	 
         **/
        function payment_fields() {

            ?>	
            <script>
                jQuery(document).ready(function(){
                    jQuery('button').click(function(){
                        var str = jQuery('p.order_number').text();
                        if (str.search('Credit Card Payments') == 12) {
                            jQuery('.check-column').attr('type', 'hidden');
                            jQuery('.check-column>input').attr('type', 'hidden');
                        }
                    });
                    jQuery('button.cancel-action').click(function(){
                        jQuery('.check-column').attr('type', 'checkbox');
                        jQuery('.check-column>input').attr('type', 'checkbox');
                    });
                });
            </script>
            <style>
                    .txt {
                            border: 1px solid #ccc;
                            padding: 3px !important;
                    }
                    .lbs{
                            display:block;
                            margin-top: 10px;
                    }
            </style>
            <div id="result"></div>
            <div>
                <label class="lbs">Card holder name</label>
                <input id="card_holder_name" class="txt" size="30" type="text" value="ashish" name="card_owner" />
            </div>
            <div>
                <label class="lbs">Card Type</label>
                <select id="cardtype" class="txt" name="cardtype" >
                <option value="Visa">Visa</option>
                <option value="MasterCard">MasterCard</option>
                <option value="Discover">Discover</option>
                <option value="AmericanExpress">American Express</option>
                </select>
            </div>
            <div>
                <label class="lbs">Credit Card Number: </label>
                <input id="cc-number" type="text" maxlength="16" class="txt" autocomplete="off" value="4012888812348882" autofocus name="cardnumber" />
            </div>
            <div>
                <label class="lbs">CVC: </label>
                <input id="cc-cvc" type="text" maxlength="4" class="txt" autocomplete="off" value="123" name="cvvnumber" />
            </div>
            <div>
                <label class="lbs">Expiry Date: </label>
                <select id="cc-exp-month" class="txt" name="exp_month">
                    <option value="01">Jan</option>
                    <option value="02">Feb</option>
                    <option value="03">Mar</option>
                    <option value="04">Apr</option>
                    <option value="05">May</option>
                    <option value="06">Jun</option>
                    <option value="07">Jul</option>
                    <option value="08">Aug</option>
                    <option value="09">Sep</option>
                    <option value="10">Oct</option>
                    <option value="11">Nov</option>
                    <option value="12">Dec</option>
                </select>
                <select id="cc-exp-year" class="txt" name="exp_year">
                    <option value="15">2015</option>
                    <option value="16">2016</option>
                    <option value="17">2017</option>
                    <option value="18">2018</option>
                    <option value="19">2019</option>
                    <option value="20">2020</option>
                    <option value="21">2021</option>
                    <option value="22">2022</option>
                </select>
            </div>

        <?php		
        }

        /**
         * Process the payment and return the result
         * @param int $order_id this is use to process the order on basis of this id and also update the payment transaction for this order.
         * @return array with Success and url with order object.
         * throw error message on failure of payment.
         **/
        function process_payment($order_id) { 

            // collect the data for payment by PHP SDK.
            global $woocommerce;
            $order    = new WC_Order( $order_id );
            $user     = wp_get_current_user();
            $address  = get_user_meta($user->ID, 'billing_address_1', true);
            $city     = get_user_meta($user->ID, 'billing_city', true);
            $state    = get_user_meta($user->ID, 'billing_state', true);
            $postcode = get_user_meta($user->ID, 'billing_postcode', true);
            $country  = get_user_meta($user->ID, 'billing_country', true);
            $phone    = get_user_meta($user->ID, 'billing_phone', true);
            $total    = $woocommerce->cart->total;

            // create the avsdata array for pass in SDK method.
            $avsData  = array('Street' => $address, 'City' => $city, 'StateProvince' => $state, 'PostalCode' => $postcode, 'Country' => $country, 'Phone' => $phone);
            // create the carddata array for pass in SDK method.
            $cardData = array('cardowner' => $_POST['card_owner'], 'cardtype' => $_POST['cardtype'], 'pan' => $_POST['cardnumber'], 'expire' => $_POST['exp_month'].$_POST['exp_year'], 'cvv' => $_POST['cvvnumber'], 'track1data' => '', 'tarck2data' => '');

            // include the PHP SDK.
            require_once 'sdk/Velocity.php';

            // create SDK object to call the all SDK methods and genrate the sessiontoken.

            try {
                $obj_transaction = new VelocityProcessor( $this->applicationprofileid, $this->merchantprofileid, $this->workflowid, $this->isTestAccount, $this->identitytoken, null);
            } catch(Exception $e) {
                if(strcmp($e->getMessage(), 'An invalid security token was provided') == 0)
                    throw new Exception('Your order cannot be completed at this time. Please contact customer care. Error Code 8124');
                else
                    throw new Exception($e->getMessage());
            } 

            try {

                $res_authandcap = $obj_transaction->authorizeAndCapture( array(
                                                                                'amount'     => $total, 
                                                                                'token'      => NULL, 
                                                                                'avsdata'    => $avsData, 
                                                                                'carddata'   => $cardData, 
                                                                                'invoice_no' => '',
                                                                                'order_id'   => $order_id
                                                                                )
                                                                        );
                //print_r($res_authandcap);die;
                global $wpdb;
                $velocity_transaction_table = $wpdb->prefix . 'velocity_transaction'; // table name which is use to save the transaction data. 

                if (is_array($res_authandcap) && isset($res_authandcap['StatusCode']) && $res_authandcap['StatusCode'] == '000' ) { // check the transaction success or failure.

                    try {
                        $xml = VelocityXmlCreator::authorizeandcaptureXML( array(
                                                                            'amount'     => $total, 
                                                                            'token'      => NULL, 
                                                                            'avsdata'    => $avsData, 
                                                                            'carddata'   => $cardData, 
                                                                            'invoice_no' => '',
                                                                            'order_id'   => $order_id
                                                                            )                                         
                                                                  );  // got authorizeandcapture xml object. 

                        $req = $xml->saveXML();
                        $obj_req = serialize($req);

                    } catch (Exception $e) {
                        throw new Exception($e->getMessage());
                    }

                    $transaction_id     = $res_authandcap['TransactionId'];
                    $transaction_status = $res_authandcap['TransactionState'];
                    $obj_res            = serialize($res_authandcap);

                    if($order->status !== 'completed') {

                        $order->payment_complete($transaction_id);
                        $order->add_order_note('Credit Card payment successful.<br/>Velocity Trasaction ID: '.$transaction_id);
                        $woocommerce->cart->empty_cart();

                        $wpdb->insert( 
                        $velocity_transaction_table, 
                        array( 
                                'id'                 => '', 
                                'transaction_id'     => $transaction_id,
                                'transaction_status' => $transaction_status,
                                'order_id'           => $order_id,
                                'request_obj'        => $obj_req,
                                'response_obj'       => $obj_res
                        ), 
                        array( 
                                '%d', 
                                '%s',
                                '%s',
                                '%s',
                                '%s',
                                '%s'		
                        ) 
                        );

                    }

                    // here check the version of WooCommerce
                    if (version_compare(WOOCOMMERCE_VERSION, '2.1.0', '>=')) {
                        /* 2.1.0 */
                        $checkout_payment_url = $order->get_checkout_payment_url(true);
                    } else {
                        /* 2.0.0 */
                        $checkout_payment_url = get_permalink(get_option('woocommerce_pay_page_id'));
                    }

                    // retuen the data with current status.
                    return array(
                        'result'   => 'success',
                        'redirect' => $this->get_return_url( $order )
                    );

                } else if (is_array($res_authandcap) && isset($res_authandcap['StatusCode']) && $res_authandcap['StatusCode'] != '000') {
                    throw new Exception($res_authandcap['StatusMessage']);
                } else if (is_string($res_authandcap)) {
                    if (strcmp(trim($res_authandcap) , 'ApplicationProfileId is not valid.<br>') == 0)
                        throw new Exception('Your order cannot be completed at this time. Please contact customer care. Error Code 1010');
                    else if (strip_tags(strstr($res_authandcap , $this->workflowid)) == $this->workflowid)
                        throw new Exception('Your order cannot be completed at this time. Please contact customer care. Error Code 9621');   
                    else if (strlen($res_authandcap) == 702) 
                        throw new Exception('Your order cannot be completed at this time. Please contact customer care. Error Code 2408');                                        
                    else
                        throw new Exception($res_authandcap);
                } else {
                    throw new Exception('Unkown Error occurs please contact the site admin or technical team.');
                }
                
            } catch (Exception $ex) {
                    throw new Exception($ex->getMessage());
            }	

        }

    } // class end

    /**
     * Add the Gateway to WooCommerce
     **/
    function woocommerce_add_velocity_gateway($methods) {
            $methods[] = 'WC_Velocity';
            return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_velocity_gateway');
}


// here we get the current version of database version
function get_db_version() {
   global $wpdb;
   $row = $wpdb->get_results("SELECT VERSION() as VERSION");
   return $row[0]->VERSION; 
}

$velocity_db_version = get_db_version();

// this method added table in data if not exist.
function velocity_install() {
    global $wpdb;
    global $velocity_db_version;
    $velocity_transaction_table = $wpdb->prefix . 'velocity_transaction';

    /*
     * We'll set the default character set and collation for this table.
     * If we don't do this, some characters could end up being converted 
     * to just ?'s when saved in our table.
     */
    $charset_collate = '';

    if (!empty($wpdb->charset)) {
      $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }

    if (!empty($wpdb->collate)) {
      $charset_collate .= " COLLATE {$wpdb->collate}";
    }

    // table structure define here.
    $sql = "CREATE TABLE $velocity_transaction_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        transaction_id varchar(220) DEFAULT '' NOT NULL,
        transaction_status varchar(100) DEFAULT '' NOT NULL,
        order_id varchar(20) DEFAULT '' NOT NULL,
        request_obj text NOT NULL,
        response_obj text NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    // include wordpress file to work database task.
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta( $sql );

    add_option('jal_db_version', $at_db_version);
}

// hook work at the time of plugin activation create table in database.
register_activation_hook(__FILE__, 'velocity_install'); 
