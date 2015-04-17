<!--
User story ID: OMI_014
Author: Heini L. Ovason
-->

<?php
/*
 * The variable $verificationMsg should be set to the returned String value from the
 * 
 */
$verificationMsg;

// Temp variable result.
$verificationResult = false;

if ($verificationResult) {
    $verificationMsg = "Account Verified!";
    header("refresh:3; url=../index.php");
} else {
    $verificationMsg = "Account NOT Verified!";
}
?>

<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 text-center">
            <h2><?php echo $verificationMsg; ?></h2>
        </div>
    </div>
</div> <!-- END container -->







