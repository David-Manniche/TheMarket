<?php defined('SYSTEM_INIT') or die('Invalid Usage . ');
if ( $trackingInfo['meta']['code'] != 200 ){ ?>
<h4><?php echo $trackingInfo['meta']['code'].' : '.$trackingInfo['meta']['message']; ?></h4>
<?php }else{  ?>

<div>
    <?php echo Labels::getLabel('LBL_Tracking_Number', $siteLangId).": ".$trackingInfo['data']['tracking']['tracking_number'];  ?>
</div>
<div><?php echo Labels::getLabel('LBL_Courier', $siteLangId).": ".$trackingInfo['data']['tracking']['slug'];  ?></div>
<div><?php echo Labels::getLabel('LBL_status', $siteLangId).": ".$trackingInfo['data']['tracking']['tag'];  ?></div>
<?php 
		if(!empty($trackingInfo['data']['tracking']['order_promised_delivery_date'])) { 
			echo "<div>".Labels::getLabel('LBL_order_promised_delivery_date', $siteLangId).": ".FatDate::format($trackingInfo['data']['tracking']['order_promised_delivery_date'])."</div>";
		} 
		
		if(!empty($trackingInfo['data']['tracking']['courier_tracking_link'])) { 
			echo "<div>".Labels::getLabel('LBL_courier_tracking_link', $siteLangId).": ".$trackingInfo['data']['tracking']['courier_tracking_link']."</div>";
		}
		
		if(!empty($trackingInfo['data']['tracking']['checkpoints'])) { 
			echo "<div><ul>";
			foreach($trackingInfo['data']['tracking']['checkpoints'] as $data){
				echo "<li class='mt-5'>";
				echo "<div>".FatDate::format($data['checkpoint_time'], true)."</div>";
				echo "<div>".$data['tag']."</div>";
				echo "<div>".$data['slug']."</div>";
				echo "<div>".$data['message']."</div>";
				echo "<div>";
				if(!empty($data['location'])){
					echo $data['location'].", ";
				}
				if(!empty($data['city'])){
					echo $data['city'].", ";
				}
				if(!empty($data['state'])){
					echo $data['state'].", ";
				}
				if(!empty($data['country_name'])){
					echo $data['country_name'].", ";
				}
				if(!empty($data['zip'])){
					echo $data['zip'];
				}
				echo "</div>";
				echo "</li>";
			
			}
			echo "</ul></div>";
		}
	} 
?>
<ul class="timeline" id="timeline">

    <li class="event" data-date="30-Jun-2020 6:52 AM">
        <!-- Toggle -->

        <div class="status">
            <p> <strong> Status goes here </strong></p>
            <p>Status text goes here</p>
        </div>

    </li>


    <li class="event" data-date="30-Jun-2020 6:52 AM">
        <!-- Toggle -->
        <a class="dropdown-toggle collapsed" data-toggle="collapse" href="#event1" aria-expanded="false">
            <span class="timeline_status"><svg class="svg" width="16px" height="16px">
                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#in-progress"
                        href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#in-progress">
                    </use>
                </svg> In-progress</span>
            You edited this order.</a>

        <!-- Collapse -->
        <div class="collapse" id="event1" data-parent="#timeline">
            <div class="mt-3">
                <p>
                    Saw wherein fruitful good days image
                    them, midst, waters upon, saw. Seas
                    lights seasons. Fourth
                    hath rule creepeth own lesser years
                    itself so seed fifth for grass.
                </p>
            </div>
        </div>
    </li>
    <li class="event" data-date="30-Jun-2020 6:52 AM">
        <!-- Toggle -->
        <a class="dropdown-toggle collapsed" data-toggle="collapse" href="#event2" aria-expanded="false">You added a
            shipping
            address to this order.

        </a>

        <!-- Collapse -->
        <div class="collapse" id="event2" data-parent="#timeline">
            <div class="mt-3">
                <p><strong>Address</strong><br>
                    jimmy choo<br>
                    sector 46<br>
                    345<br>
                    160008 Chandigarh CH<br>
                    India
                </p>
            </div>
        </div>
    </li>
    <li class="event" data-date="30-Jun-2020 6:52 AM">
        <!-- Toggle -->
        <a class="dropdown-toggle collapsed" data-toggle="collapse" href="#event3" aria-expanded="false">
            <span class="timeline_status"><svg class="svg" width="16px" height="16px">
                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#ready-for-shipping"
                        href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#ready-for-shipping">
                    </use>
                </svg> Ready for Shipped</span>
            You made 2 changes to this
            order.

        </a>

        <!-- Collapse -->
        <div class="collapse" id="event3" data-parent="#timeline">
            <div class="mt-3">
                <p>
                    <strong>Email</strong><br>
                    added jimmy@dummyid.com<br>
                    <strong>Phone</strong><br>
                    added +919845786598
                </p>
            </div>
        </div>
    </li>
    <li class="event" data-date="30-Jun-2020 6:52 AM">
        <!-- Toggle -->
        <a class="dropdown-toggle collapsed" data-toggle="collapse" href="#event4" aria-expanded="false">You edited the
            note on
            this order.

        </a>

        <!-- Collapse -->
        <div class="collapse" id="event4" data-parent="#timeline">
            <div class="mt-3">
                <p>
                    I would like you to gift wrap this
                    product as it is my mothers birthday
                </p>
            </div>
        </div>
    </li>



    <li class="event" data-date="30-Jun-2020 6:52 AM">
        <!-- Toggle -->
        <a class="dropdown-toggle" data-toggle="collapse" href="#event5" aria-expanded="true">

            <span class="timeline_status"><svg class="svg" width="16px" height="16px">
                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#shipped"
                        href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#shipped">
                    </use>
                </svg> Shipped</span>
            You added a note to this
            order.
        </a>

        <!-- Collapse -->
        <div class="collapse" id="event5" data-parent="#timeline" style="">
            <div class="mt-3">

                <div class="info-box">
                    <h6>Track your order</h6>
                    <p class="mb-2">You can track your order
                        process in your
                        details and you can see the location
                        of your
                        item</p>
                </div>

                <div class="row">
                    <div class="col-auto">
                        <span class="bold"> <strong>Courier
                            </strong></span>
                        <p>DHL</p>
                    </div>
                    <div class="col">
                        <span class="bold"><strong>Tracking
                                reference </strong></span>
                        <p>2342345</p>
                    </div>
                </div>
            </div>
        </div>
    </li>

    <li class="event" data-date="30-Jun-2020 6:52 AM">
        <!-- Toggle -->
        <a class="dropdown-toggle collapsed" data-toggle="collapse" href="#event6" aria-expanded="false">You edited this
            order.</a>

        <!-- Collapse -->
        <div class="collapse" id="event6" data-parent="#timeline">
            <div class="mt-3">
                <p>
                    Saw wherein fruitful good days image
                    them, midst, waters upon, saw. Seas
                    lights seasons. Fourth<br><br>
                    hath rule creepeth own lesser years
                    itself so seed fifth for grass.
                </p>
            </div>
        </div>
    </li>
    <li class="event" data-date="30-Jun-2020 6:52 AM">
        <!-- Toggle -->
        <a class="dropdown-toggle collapsed" data-toggle="collapse" href="#event7" aria-expanded="false">You edited this
            order.</a>

        <!-- Collapse -->
        <div class="collapse" id="event7" data-parent="#timeline" style="">
            <div class="mt-3">
                <p>
                    Saw wherein fruitful good days image
                    them, midst, waters upon, saw. Seas
                    lights seasons. Fourth
                    hath rule creepeth own lesser years
                    itself so seed fifth for grass.
                </p>
            </div>
        </div>
    </li>
    <li class="event" data-date="30-Jun-2020 6:52 AM">
        <!-- Toggle -->
        <a class="dropdown-toggle collapsed" data-toggle="collapse" href="#event8" aria-expanded="false">

            <span class="timeline_status"><svg class="svg" width="16px" height="16px">
                    <use xlink:href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#delivered"
                        href="<?php echo CONF_WEBROOT_URL; ?>images/retina/sprite.svg#delivered">
                    </use>
                </svg> Delivered</span>


            You edited this order.</a>

        <!-- Collapse -->
        <div class="collapse" id="event8" data-parent="#timeline">
            <div class="mt-3">
                <p>
                    Saw wherein fruitful good days image
                    them, midst, waters upon, saw. Seas
                    lights seasons. Fourth
                    hath rule creepeth own lesser years
                    itself so seed fifth for grass.
                </p>
            </div>
        </div>
    </li>

</ul>