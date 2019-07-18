<style>.-fixed-wrap{
  position: fixed;
  bottom: 1rem;
  left: 1rem;
  z-index: 9999;
}
.-fixed-wrap a{
  position: relative;
  display: inline-block;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  border: none;
  border-radius: 2px;
  padding: 2.25rem 1rem 0.5rem;
  vertical-align: middle;
  -webkit-border-radius: 5px;
  border-radius: 5px;
  text-align: center;
  text-overflow: ellipsis;
  text-transform: uppercase;
  color: #fff;
  background: #666;
  text-decoration: none;
  font-size: 2rem;
  letter-spacing: 0.15em;
  overflow: hidden;
  min-width:200px;
}
.-fixed-wrap a small{
  position: absolute;
  top: 0;
  left: 0; right: 0;
  display: block;
  padding: 0.5rem 1rem;
  font-size: 0.75rem;
  letter-spacing: 0.05em;
  white-space: nowrap;
  background-color: rgba(0,0,0,0.2);
}

.restore-demo-bg {
    background-image: url('<?php echo CommonHelper::generateFullUrl('','',array(),CONF_WEBROOT_FRONT_URL).'/images/catalog-bg.png';?>')!important;
    background-color: #fff!important;
    background-repeat: no-repeat!important;
    background-position: 130% top!important;
}

.restore-demo .demo-data-inner > ul,
.restore-demo .demo-data-inner .heading {
    max-width: 500px;
    margin-right: 250px;
}

.demo-data-inner {
    margin: 20px;
    color: #4c4c4c;
}

.demo-data-inner .heading {
    font-size: 4rem;
    font-weight: 600;
    text-transform: uppercase;
    position: relative;
    line-height: 1.2;
    margin-bottom: 40px;
    color: inherit;
}

.demo-data-inner .heading:after {
    background: var(--second-color);
    width: 60px;
    height: 3px;
    position: absolute;
    bottom: -10px;
    content: "";
    display: block;
}

.demo-data-inner .heading span {
    display: block;
    font-size: 0.8rem;
    text-transform: none;
}

.demo-data-inner ul li {
    position: relative;
    margin: 10px 0;
    padding: 0 15px;
    display: block;
    font-size: 0.9rem;
}

.demo-data-inner ul li:before {
    width: 5px;
    height: 5px;
    content: "";
    display: block;
    position: absolute;
    left: 0;
    top: 8px;
    transform: rotate(45deg);
    background: #4c4c4c;
}

.demo-data-inner ul ul {
    margin-inline-start: 15px;
    margin-bottom: 20px;
}

</style>
<div class="-fixed-wrap">
  <a href="javascript:void(0)" onClick="showRestorePopup()">
    <small>Database Will Restore in</small>
    <span id="restoreCounter">00:00:00</span>
  </a>
</div>
<script>
<?php
$dateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').' +2 hours'));
$restoreTime = FatApp::getConfig('CONF_RESTORE_SCHEDULE_TIME', FatUtility::VAR_STRING, $dateTime);
?>
// Set the date we're counting down to
var countDownDate = new Date('<?php echo $restoreTime;?>').getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

    // Get today's date and time
    var now = new Date().getTime();

    // Find the distance between now and the count down date
    var distance = countDownDate - now;

    // Time calculations for days, hours, minutes and seconds
    // var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    var str  = ('0' + hours).slice(-2) + ":" + ('0' + minutes).slice(-2) + ":" + ('0' + seconds).slice(-2);
    // Display the result in the element with id="demo"
    $('#restoreCounter').html(str);

    // If the count down is finished, write some text
    if (distance < 0) {
        clearInterval(x);
        $('#restoreCounter').html("Process...");
        showRestorePopup();
        restoreSystem();
    }
}, 1000);
function showRestorePopup(){
    $.facebox('<div class="demo-data-inner"><div class="heading">Yo!kart<span></span></div> <p>To enhance your demo experience, we periodically  restore our database every 4 hours.</p><br> <p>For technical issues :-</p> <ul> <li><strong>Call us at: </strong>+1 469 844 3346, +91 85919 19191, +91 95555 96666, +91 73075 70707, +91 93565 35757</li> <li><strong>Mail us at : </strong> <a href="mailto:sales@fatbit.com">sales@fatbit.com</a></li> </ul> <br> Create Your Dream Multi-vendor Ecommerce Store With Yo!Kart <a href="https://www.yo-kart.com/contact-us.html" target="_blank">Click here</a></li></div>','restore-demo restore-demo-bg');
}

function restoreSystem(){ return false;
    $.mbsmessage('Restore is in process..',false,'alert--process alert');
    fcom.updateWithAjax(fcom.makeUrl('RestoreSystem','index','','<?php echo CONF_WEBROOT_FRONT_URL; ?>'), '', function(resp){
        setTimeout(function(){ window.location.reload(); }, 3000);
    },false,false);
}
</script>
