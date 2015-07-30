<!DOCTYPE html>

<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Campaign Finances Power Search</title>
    <link rel="stylesheet" type="text/css" href="css/caps.css" media="all">
    <script src="js/jquery.js"></script>
    <script src="js/caps.js"></script>
</head>

<body>
<?php
# Campaign Finance Power Search Project
# Written by Mike Krejci for MapLight

# Load required libraries
require("connect.php");
require("sidebar.php");
require("results.php");
?>

<!-- default header, replace with a custom header -->
<div id="caps_header">
    <div><img id="maplight_logo" src="img/MapLight_Demo.jpg" style="margin-left:10px; margin-bottom:6px;" alt="MapLight: Revealing Money's Influence on Politics"></div>
    <div id="caps_menu"><ul>
            <li><b>Campaign Finance Power Search</b></li>
            <li><a href="index.php">Quick Search</a></li>
            <li><a href="advanced.php">Advanced Search</a></li>
        </ul></div>
    <div style="border:2px solid #FF0000; background:#FFCCCC; margin:2px; color:red; text-align:center;"><b>NOTE: This search is in BETA. Please do not cite.</b></div>
</div> <!-- end caps_header -->


<div id="caps_wrapper">
    <div id="caps_container">
        <div id="qs_page">
            <div id="qs_title">
                <h1 class="font_large_header">CAL-ACCESS Campaign Power Search</h1>
                <p id="caps_description" class="strong">Search political contributions to candidates, ballot measures, and other committees from 2001 through the present.</p>
            </div> <!-- end qs_title -->

            <a id="advanced_button" href="advanced.php" class="right font_btn btn_border qs_link1">Go to Advanced Search</a>

            <div id="qs_search">
                <div class="font_title">Quick Search</div>
                <hr class="caps_hr1">

                <img src="img/qs_candidate.jpg" width="50" class="caps_img" alt="Candidate Option Icon">
                <div class="qs_box">
                    <form action="advanced.php" method="post">
                        <input type="hidden" name="quick_search" value="true">
                        <div class="font_title qs_option_title">Candidates</div>
                        <div class="left">How much has&nbsp;</div>
                        <div class="left">
                            <input type="hidden" id="match_candidate" name="match_candidate" value="no">
                            <input type="text" id="search_candidates" name="search_candidates" value="Search candidates" onkeyup="fill_candidate_list(event);" onFocus="if(this.value == 'Search candidates') {this.value = '';} fill_candidate_list(event);" onBlur="if(this.value == '') {this.value = 'Search candidates';}" class="font_input input_border qs_text1" alt="Search Candidates"><br>
                            <div id="candidates" class="input_border qs_search_dropbox"></div>
                        </div>
                        <div class="left">&nbsp;received?</div><br>
                        <input type="submit" class="qs_btn" name="qs_button" value="Search Candidates">
                    </form>
                </div> <!-- end qs_box(Candidates) -->
                <hr class="caps_hr1">

                    <img src="img/qs_ballot.jpg" width="50" class="caps_img" alt="Ballot Measures Option Icon">
                    <div class="qs_box">
                        <form action="advanced.php" method="post">
                            <input type="hidden" name="quick_search" value="true">
                            <div class="font_title qs_option_title">Ballot Measures</div>
                            How much has been raised for all measures on the <select id="propositions_list" name="proposition_list" class="font_input input_border qs_select">
                                <?php
                                # Fill election dates dropdown
                                fill_qs_elections();
                                ?>
                            </select> ballot?<br>
                            <input type="submit" class="qs_btn" name="qs_button" value="Search Ballot Measures">
                        </form>
                    </div> <!-- end qs_box -->
                    <hr class="caps_hr1">

                    <img src="img/qs_contributor.jpg" width="50" class="caps_img" alt="Contributors Option Icon">
                    <div class="qs_box">
                        <form action="advanced.php" method="post">
                            <input type="hidden" name="quick_search" value="true">
                            <div class="font_title qs_option_title">Contributors</div>
                            How much has <input type="text" id="contributor" name="contributor" value="company, organization, or person" onFocus="if(this.value == 'company, organization, or person') {this.value = '';}" onBlur="if(this.value == '') {this.value = 'company, organization, or person';}" class="font_input input_border qs_text2" alt="Search Contributors"> contributed?<br>
                            <input type="submit" class="qs_btn" name="qs_button" value="Search Contributors">
                        </form>
                    </div> <!-- end qs_box -->
                    <hr class="caps_hr1">

                    <img src="img/qs_advanced.jpg" width="50" class="caps_img" alt="Advanced Search Option Icon">
                    <div class="qs_box">
                        <div class="font_title qs_option_title"><a href="advanced.php">Advanced Search</a></div>Search by date, committee name, and more.
                    </div> <!-- end qs_box -->
                </div> <!-- end qs_search -->
            </form>

            <div id="maplight_info">Power Search software by <a href="http://www.maplight.org">MapLight</a><br><div class="center"><img src="img/MapLight_Logo.png" width="80" alt="MapLight Logo"></div></div>

        </div> <!-- end qs_page -->
    </div> <!-- end caps_containter -->
</div> <!-- end caps_wrapper-->

<!-- Place custom page footer here
<footer></footer>
-->
</body>
</html>

