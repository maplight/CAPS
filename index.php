<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>California Secretary of State</title>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main-max-width.css">
  <noscript><link href="css/noJs.css" rel="stylesheet" type="text/css" /></noscript>
  <!--[if lt IE 9]>
    <link rel="stylesheet" href="css/ie-main-max-width.css" media="screen" />
    <script src="js/vendor/compatibility.js"></script>
  <![endif]-->
  <link rel="stylesheet" type="text/css" href="css/caps.css" media="all">
  <script src="js/vendor/ga-async.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/caps.js"></script>
</head>

<body>
  <?php
    # Cal-Access Power Search Project
    # MapLight
    # Mike Krejci

    # Load required libraries
    require ("connect.php");
    require ("sidebar.php");
    require ("results.php");
  ?>

  <!-- main container of all the page elements -->
  <div id="wrapper">
    <!-- container of the page -->
    <div id="container">

      <!-- header of the page -->
      <div id="header" a>


<!-- California SOS header page -->
    <div class="frme" id="top">
    <!-- utility navigation -->
    <div id="utl" class="clearfix">
        <ul>
        <li><a href="http://registertovote.ca.gov/#mainCont">Skip to Main Content</a></li>
        <li class="listInlineLastChild"><a href="http://registertovote.ca.gov/#footer">Skip to Footer</a></li>
        </ul>
    </div>
    <!-- // utility navigation -->
    <!-- banner -->
    <div id="bnrCtnr" role="banner">
    <div id="bnrLgoTxt">California Secretary of State Debra Bowen</div>
    </div>
    <!-- // banner -->
    <!-- mobile menu(s) -->
    <div class="mainNavTogBtn" aria-labeledby="nav" aria-describedby="mainNavTogCont"><a href="#"><span class="visuallyhidden">Navigation Menu</span></a></div>
    <!-- //mobile menu(s) -->
    </div><!-- end of "frme" -->
    <!-- SITE NAVIGATION -->
    <div id="mainNavCtnr">
    <div id="mainNavCtnd">
    <nav id="nav" aria-label="Main">
    <div class="nav-grid-75">
    <div id="mainNavTogCont" aria-hidden="false">
        <!-- main navigation -->
        <ul>
        <li><a href="http://registertovote.ca.gov//help/">Website Help</a></li>
        </ul>
        <!-- // main navigation -->
    </div>
    </div><!-- end of .nav-grid-75 -->
    <div class="clear"></div><!-- clear needed to maintain the full height of nav bar -->
    </nav><!--end of #nav-->
    </div><!--end of .mainNavCtnd-->
    </div><!--end of .mainNavCtnr-->

      </div>


      <!-- two columns of the page -->
      <div id="two-columns">
        <!-- contain sidebar of the page -->
        <div id="sidebar">
          <!-- search form of the page -->
          <form action="#" class="search-form">
            <fieldset>
              <legend class="hidden">search-form</legend>
              <h1>Advanced Search</h1>
              <input type="submit" value="Search" tabindex="1">
              <div class="holder-form">
                <!-- section of search form -->
                <div class="form-section">
                <h2>Contributions From:</h2>
                <div class="radio-holder">
                  <div class="row info">
                    <input type="radio" id="all" name="all" tabindex="2">
                    <label for="all">All contributors</label>
                    <a href="#" class="info" tabindex="3">info</a>
                  </div>
                  <div class="row">
                    <input type="radio" name="all" tabindex="4" id="for1">
                    <label for="for1" class="hidden">label</label>
                    <label for="for2" class="hidden">label</label>
                    <input type="text" id="for2" value="Just these contributors" tabindex="5" accesskey="s">
                  </div>
                </div>
                <div class="contry-select">
                  <label for="contr">Contributor Location</label>
                  <a href="#" class="info" tabindex="6">info</a>
                  <select tabindex="7" id="contr">
                    <option>All locations</option>
                    <option> location1</option>
                    <option> location2</option>
                    <option> location3</option>
                    <option> location4</option>
                  </select>
                </div>
              </div>
              <!-- section of search form -->
              <div class="form-section middel">
                <h2>Contributions To:</h2>
                <div class="frame">
                  <div class="sub-section">
                  <div class="check-part">
                    <input type="checkbox" id="cand" tabindex="8">
                    <label for="cand">Candidates</label>
                    <a href="#" class="info" tabindex="9">info</a>
                  </div>
                  <div class="holder-b">
                    <div class="sub-row">
                      <input type="radio" id="all1" name="to" tabindex="10">
                      <label for="all1">All candidates</label>
                    </div>
                    <div class="sub-row info">
                      <input type="radio" id="for3" name="to" tabindex="11">
                      <label for="for3" class="hidden">label</label>
                      <label for="for4" class="hidden">label</label>
                      <input type="text" id="for4" value="Just these candidates" tabindex="12">
                      <a href="#" class="info" tabindex="13">info</a>
                    </div>
                    <div class="sub-row info">
                      <input type="radio" name="to" tabindex="14" id="for5">
                      <label for="for5" class="hidden">label</label>
                      <label for="for6" class="hidden">label</label>
                      <select tabindex="15" id="for6">
                        <option>Candidates for these offices</option>
                        <option>Candidates 1</option>
                        <option>Candidates 2</option>
                        <option>Candidates 3</option>
                      </select>
                      <a href="#" class="info" tabindex="16">info</a>
                    </div>
                  </div>
                </div>
                  <!--sub section of search form -->
                  <div class="sub-section">
                  <div class="check-part">
                    <input type="checkbox" id="cand1" tabindex="17">
                    <label for="cand1">Ballot Measures</label>
                    <a href="#" class="info"tabindex="18">info</a>
                  </div>
                  <div class="holder-b add">
                    <div class="sub-row">
                      <label for="for7" class="hidden">label</label>
                      <select tabindex="19" id="for7">
                        <option>All elections</option>
                        <option>elections 1</option>
                        <option>elections 2</option>
                        <option>elections 3</option>
                      </select>
                    </div>
                    <div class="sub-row">
                      <label for="for8" class="hidden">label</label>
                      <select id="for8" tabindex="20">
                        <option>All propositions</option>
                        <option>elections 1</option>
                        <option>elections 2</option>
                        <option>elections 3</option>
                      </select>
                    </div>
                    <div class="sub-row">
                      <label for="for9" class="hidden">label</label>
                      <select id="for9" tabindex="21">
                        <option>Both support &amp; oppose</option>
                        <option>elections 1</option>
                        <option>elections 2</option>
                        <option>elections 3</option>
                      </select>
                    </div>
                    <div class="check-b-area">
                      <input type="checkbox" id="alli" tabindex="22">
                      <label for="alli">Exclude contnbutions between allied committees</label>
                    </div>
                  </div>
                </div>
                  <!-- sub section of search form -->
                  <div class="sub-section last">
                  <div class="check-part">
                    <input type="checkbox" id="com">
                    <label for="com">Committees</label>
                    <a href="#" class="info" tabindex="23">info</a>
                  </div>
                  <div class="holder-b add">
                    <div class="sub-row">
                      <input type="radio" id="allc" name="comm">
                      <label for="allc">All committees</label>
                    </div>
                    <div class="sub-row">
                      <input type="radio" name="comm" id="for10">
                      <label for="for10" class="hidden">label</label>
                      <label for="for11" class="hidden">label</label>
                      <input type="text" value="Just these committees" id="for11">
                    </div>
                  </div>
                </div>
                </div>
              </div>
                <!-- section of search form -->
                <div class="form-section info">
                  <h2>Dates:</h2>
                  <a href="#" class="info">info</a>
                  <div class="radio-row">
                    <input type="radio" id="cyc" name="date">
                    <label for="cyc">All dates and election cycles</label>
                  </div>
                  <div class="radio-row">
                    <input type="radio" id="cyc1" name="date">
                    <label for="cyc1">Date range</label>
                    <div class="date-range">
                      <label for="for12" class="hidden">label</label>
                      <input type="text" id="for12" value="mm-dd-yyyy" class="small">
                      <label for="to" class="to-label">to</label>
                      <input type="text" value="mm-dd-yyyy" id="to" class="small">
                    </div>
                  </div>
                  <div class="radio-row">
                    <input type="radio" id="cyc2" name="date">
                    <label for="cyc2">Election cycles</label>
                    <div class="year-row">
                      <div class="year-check">
                        <input type="checkbox" id="y3">
                        <label for="y3">2013-2014</label>
                      </div>
                      <div class="year-check">
                        <input type="checkbox" id="y5">
                        <label for="y5">2005-2006</label>
                      </div>
                      <div class="year-check">
                        <input type="checkbox" id="y1">
                        <label for="y1">2011-2012</label>
                      </div>
                      <div class="year-check">
                        <input type="checkbox" id="yy3">
                        <label for="yy3">2003-2004</label>
                      </div>
                      <div class="year-check">
                        <input type="checkbox" id="y9">
                        <label for="y9">2009-2010</label>
                      </div>
                      <div class="year-check">
                        <input type="checkbox" id="yy1">
                        <label for="yy1">2001-2002</label>
                      </div>
                      <div class="year-check">
                        <input type="checkbox" id="y7">
                        <label for="y7">2007-2008</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <input type="submit" value="Search">
            </fieldset>
          </form>
        </div>
        <!-- contain the main content of the page -->
        <div id="content">
          <h1>Search Results</h1>
          <!-- info block of the content -->
          <div class="info-block">
            <div class="search-info">
              <div class="title"><strong>$5,000,000</strong> in 193 contributions <a href="#" class="info">info</a></div>
              <em>from election cycles 2011-2012 through 2013-2014</em>
            </div>
            <div class="contributions-area">
              <h2>Contributions</h2>
              <div class="output">Showing all contributions of $100 or more <a href="#" class="info">info</a></div>
            </div>
          </div>
          <!-- filter block of the content -->
          <div class="filter-block">
            <!-- filter form of the content -->
            <form action="#" class="filter-form">
              <fieldset>
                <legend class="hidden">filter-form</legend>
                <label for="show">Show</label>
                <select id="show">
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                  <option>6</option>
                  <option>7</option>
                  <option>8</option>
                  <option>9</option>
                  <option>10</option>
                  <option>11</option>
                  <option>12</option>
                  <option>13</option>
                  <option>14</option>
                  <option>15</option>
                  <option>16</option>
                  <option>17</option>
                  <option>18</option>
                  <option>19</option>
                  <option>20</option>
                  <option>21</option>
                  <option>22</option>
                  <option>23</option>
                  <option>24</option>
                  <option>25</option>
                  <option>26</option>
                  <option>27</option>
                </select>
                <label for="row">rows</label>
                <div class="holder">
                  <input type="text" value="Filter by Keyword" id="row" accesskey="f">
                  <input type="submit" value="Filter">
                <a href="#" class="info">info</a>
                </div>
              </fieldset>
            </form>
            <!-- download area of the content -->
            <div class="download-area">
              <a href="#" class="download">Download CSV</a>
              <div class="download-info">
                <a href="#" class="info-link">opener</a>
                <div class="info-slide">
                  <p>Download your search results as a CSV file</p>
                </div>
              </div>
            </div>
          </div>
          <!-- search result block-->
          <div class="serach-result">
            <div class="output">
              <p>Showing <strong>1</strong> to <strong>25</strong> of <strong>193</strong> rows </p>
            </div>
            <a href="#" class="see-more">Show more fields</a>
            <a href="#" class="info">info</a>
          </div>
          <!-- search table  of the page -->
          <div class="table-holder">
            <table title="search table" summary="search table" class="search-table">
              <thead>
                <tr>
                  <th class="col1">
                    Recipient Name
                    <div class="links">
                      <a href="#" class="upword">upword</a>
                      <a href="#" class="downword">downword</a>
                    </div>
                  </th>
                  <th class="col2">
                    Recipient Committee
                    <div class="links">
                      <a href="#" class="upword">upword</a>
                      <a href="#" class="downword">downword</a>
                    </div>
                  </th>
                  <th class="col3">
                    Office
                    <div class="links">
                      <a href="#" class="upword">upword</a>
                      <a href="#" class="downword">downword</a>
                    </div>
                  </th>
                  <th class="col4">
                    Contributor Name
                    <div class="links">
                      <a href="#" class="upword">upword</a>
                      <a href="#" class="downword">downword</a>
                    </div>
                  </th>
                  <th class="col5">
                    Contributor Employer
                    <div class="links">
                      <a href="#" class="upword">upword</a>
                      <a href="#" class="downword">downword</a>
                    </div>
                  </th>
                  <th class="col6">
                    Contributor Occupation
                    <div class="links">
                      <a href="#" class="upword">upword</a>
                      <a href="#" class="downword">downword</a>
                    </div>
                  </th>
                  <th class="col7">
                    Contributor Organization
                    <div class="links">
                      <a href="#" class="upword">upword</a>
                      <a href="#" class="downword">downword</a>
                    </div>
                  </th>
                  <th class="col8">
                    Date
                    <div class="links">
                      <a href="#" class="upword">upword</a>
                      <a href="#" class="downword">downword</a>
                    </div>
                  </th>
                  <th class="col9">
                    Amount
                    <div class="links">
                      <a href="#" class="upword">upword</a>
                      <a href="#" class="downword">downword</a>
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="col1">ALDANA JR., MANUEL</td>
                  <td class="col2">ALDANA FOR STATE ASSEMBLY</td>
                  <td class="col3">State Assembly</td>
                  <td class="col4">Avila, Lisa</td>
                  <td class="col5">None</td>
                  <td class="col6">Retired</td>
                  <td class="col7">None</td>
                  <td class="col8">2004-04-11</td>
                  <td class="col9">100</td>
                </tr>
                <tr>
                  <td class="col1"></td>
                  <td class="col2"></td>
                  <td class="col3"></td>
                  <td class="col4"></td>
                  <td class="col5"></td>
                  <td class="col6"></td>
                  <td class="col7"></td>
                  <td class="col8"></td>
                  <td class="col9"></td>
                </tr>
                <tr>
                  <td class="col1"></td>
                  <td class="col2"></td>
                  <td class="col3"></td>
                  <td class="col4"></td>
                  <td class="col5"></td>
                  <td class="col6"></td>
                  <td class="col7"></td>
                  <td class="col8"></td>
                  <td class="col9"></td>
                </tr>
                <tr>
                  <td class="col1"></td>
                  <td class="col2"></td>
                  <td class="col3"></td>
                  <td class="col4"></td>
                  <td class="col5"></td>
                  <td class="col6"></td>
                  <td class="col7"></td>
                  <td class="col8"></td>
                  <td class="col9"></td>
                </tr>
                <tr>
                  <td class="col1"></td>
                  <td class="col2"></td>
                  <td class="col3"></td>
                  <td class="col4"></td>
                  <td class="col5"></td>
                  <td class="col6"></td>
                  <td class="col7"></td>
                  <td class="col8"></td>
                  <td class="col9"></td>
                </tr>
                <tr>
                  <td class="col1"></td>
                  <td class="col2"></td>
                  <td class="col3"></td>
                  <td class="col4"></td>
                  <td class="col5"></td>
                  <td class="col6"></td>
                  <td class="col7"></td>
                  <td class="col8"></td>
                  <td class="col9"></td>
                </tr>
                <tr>
                  <td class="col1"></td>
                  <td class="col2"></td>
                  <td class="col3"></td>
                  <td class="col4"></td>
                  <td class="col5"></td>
                  <td class="col6"></td>
                  <td class="col7"></td>
                  <td class="col8"></td>
                  <td class="col9"></td>
                </tr>
                <tr>
                  <td class="col1"></td>
                  <td class="col2"></td>
                  <td class="col3"></td>
                  <td class="col4"></td>
                  <td class="col5"></td>
                  <td class="col6"></td>
                  <td class="col7"></td>
                  <td class="col8"></td>
                  <td class="col9"></td>
                </tr>
                <tr>
                  <td class="col1"></td>
                  <td class="col2"></td>
                  <td class="col3"></td>
                  <td class="col4"></td>
                  <td class="col5"></td>
                  <td class="col6"></td>
                  <td class="col7"></td>
                  <td class="col8"></td>
                  <td class="col9"></td>
                </tr>
                <tr>
                  <td class="col1"></td>
                  <td class="col2"></td>
                  <td class="col3"></td>
                  <td class="col4"></td>
                  <td class="col5"></td>
                  <td class="col6"></td>
                  <td class="col7"></td>
                  <td class="col8"></td>
                  <td class="col9"></td>
                </tr>
                <tr>
                  <td class="col1"></td>
                  <td class="col2"></td>
                  <td class="col3"></td>
                  <td class="col4"></td>
                  <td class="col5"></td>
                  <td class="col6"></td>
                  <td class="col7"></td>
                  <td class="col8"></td>
                  <td class="col9"></td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- pageination of the content -->
          <ul class="pagination">
            <li class="prev"><a href="#" >previous</a></li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#">6</a></li>
            <li>...</li>
            <li><a href="#">8</a></li>
            <li class="next"><a href="#">Next</a></li>
          </ul>
          <!-- notes block of the content -->
          <div class="notes">
            <p>To view the entire set of search results, <a href="#">download the CSV</a> file.
Contributions data is current as of [today's date].</p>
          </div>
        </div>
      </div>
      <!-- footer of the page -->
      <div id="footer">

<!-- California SOS footer page -->
    <footer id="footer" class="grid-100 grid-parent" lang="en-US">
	<!-- footer navigation -->
	<div class="clearfix">
    <div class="grid-20">
    <h2 class="togLnk">Agency</h2>
    <div class="togCont clearfix">
    <ul class="btns">
    <li><a href="//www.sos.ca.gov/">Secretary of State Main Website</a></li>
    <li><a href="//www.sos.ca.gov/elections/">Elections &amp; Voter Information</a></li>
	<li><a href="//www.sos.ca.gov/maintenance-schedule.htm">Site Maintenance Schedule</a></li>
    </ul>
    </div>
    </div>
    <div class="grid-25">
    <h2 class="togLnk">Resources</h2>
    <div class="togCont clearfix">
    <ul class="btns">
	<li><a href="/help/">Website Help</a></li>
	<li><a href="/privacy/">Privacy</a></li>
	<li><a href="/accessibility/">Accessibility</a></li>
    </ul>
    </div>
    </div>
    <div class="grid-25">
    <h2 class="togLnk">Voter Assistance Hotline</h2>
    <div class="togCont clearfix">
    <ul class="btns">
    <li><a href="tel:18003458683">(800) 345-VOTE (8683)</a></li>
    </ul>
    </div>
    </div>
    <div class="grid-20">
    <h2 class="togLnk">Connect</h2>
    <div class="togCont clearfix">
     <ul class="btns">
    <li><a href="//www.sos.ca.gov/multimedia/"><img src="//5d0cf7df673ab6a14d51-01fbb794ac405944f26ec8749fe8fe7b.r7.cf1.rackcdn.com/img/rss-32.png" width="32" height="32" alt="Subscribe to Secretary of State RSS Feeds" /> RSS</a></li>
    <li><a href="https://www.facebook.com/CaliforniaSOS/"><img src="//5d0cf7df673ab6a14d51-01fbb794ac405944f26ec8749fe8fe7b.r7.cf1.rackcdn.com/img/facebook-32.png" width="32" height="32" alt="Like the Secretary of State Facebook page" /> Facebook</a></li>
    <li><a href="https://twitter.com/sosnews/"><img src="//5d0cf7df673ab6a14d51-01fbb794ac405944f26ec8749fe8fe7b.r7.cf1.rackcdn.com/img/twitter-32.png" width="32" height="32" alt="Follow Secretary of State on Twitter" /> Twitter</a></li>
    </ul>    </div>
    </div>
	<!-- // footer navigation -->
    </div>
	<!-- copyright -->
    <div id="cpyrght" role="contentinfo">
    <ul>
    <li>Copyright &#169; 2013 California Secretary of State</li>
    <li>1500 11th Street, Sacramento, California 95814</li>
    <li class="listInlineLastChild">(916) 653-6814</li>
    </ul>
    <ul>
    <li><a href="http://registertovote.ca.gov/#top">Back to Top</a></li>
    <li><a href="http://registertovote.ca.gov/">Homepage</a></li>
    <li class="listInlineLastChild"><a href="//www.sos.ca.gov/free-doc-readers.htm">Free Document Readers</a></li>
    </ul>
	<!-- // COPYRIGHT -->
    </div>
    </footer>

      </div>
    </div>
    <div class="skip"><a href="#header">back to top</a></div>
  </div>


</body>
</html>
