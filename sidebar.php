<?php
  # Search form HTML

  function build_sidebar_form () {
    echo "<FORM METHOD=POST>";

    echo "<B STYLE=\"font-size:1.6em;\">Advanced Search</B><P>";
    echo "<P><INPUT ID=search TYPE=SUBMIT VALUE=\"Search\"></P>";

    echo "<B STYLE=\"font-size:1.2em; line-height:200%;\">Contributions From:</B><BR>";
    echo "Contributor name contains:<BR><INPUT TYPE=TEXT NAME=contributor STYLE=\"width:95%;\"><BR STYLE=\"line-height:190%;\">";
    echo "Contributor Location:<BR><SELECT MULTIPLE NAME=location_list[] ID=location_list STYLE=\"width:95%;\"><OPTION VALUE=\"ALL\" SELECTED>-- All states</OPTION>";
    fill_state_list ();
    echo "</SELECT><HR STYLE=\"width:98%;\">";

    echo "<B STYLE=\"font-size:1.2em;\">Contributions To:</B><BR>";
    echo "<B STYLE=\"line-height:160%;\">Candidates:</B><BR>";
    echo "Search candidates:<BR><INPUT TYPE=TEXT NAME=search_candidates ID=search_candidates STYLE=\"width:95%;\" onkeyup=\"filter_candidates_list();\"><BR>";
    echo "<SELECT MULTIPLE NAME=candidates_list[] ID=candidates_list STYLE=\"width:95%;\"><OPTION VALUE=\"ALL\" SELECTED>-- All candidates</OPTION>";
    $js_candidates = fill_candidate_names ();
    echo "</SELECT><BR STYLE=\"line-height:190%;\">";
    echo "Office sought<BR><SELECT MULTIPLE NAME=office_list[] ID=office_list STYLE=\"width:95%;\"><OPTION VALUE=\"ALL\" SELECTED>-- All offices</OPTION>";
    fill_offices_sought ();
    echo "</SELECT><P>";

    echo "<B STYLE=\"line-height:160%;\">Ballot Measures:</B><BR>";
    echo "Select election<BR><SELECT MULTIPLE NAME=elections_list[] ID=election_list STYLE=\"width:95%;\"><OPTION VALUE=\"ALL\" SELECTED>-- All elections</OPTION>";
    fill_elections ();
    echo "</SELECT><BR STYLE=\"line-height:190%;\">";
    echo "Search propositions:<BR><INPUT TYPE=TEXT NAME=search_propositions ID=search_propositions STYLE=\"width:95%;\" onkeyup=\"filter_propositions_list();\"><BR>";
    echo "<SELECT NAME=propositions_list ID=propositions_list STYLE=\"width:95%;\"><OPTION VALUE=\"ALL\">-- All propositions</OPTION>";
    $js_propositions = fill_propositions ();
    echo "</SELECT><BR STYLE=\"line-height:190%;\">";
    echo "Support & Oppose<BR>";
    echo "<INPUT NAME=support TYPE=CHECKBOX CHECKED> Support<BR>";
    echo "<INPUT NAME=oppose TYPE=CHECKBOX CHECKED> Oppose<BR>";
    echo "<INPUT NAME=exclude TYPE=CHECKBOX> Exclude contributions between allied committees<P>";

    echo "<B STYLE=\"line-height:160%;\">Committees:</B><BR>";
    echo "Committee name contains<BR><INPUT TYPE=TEXT NAME=committee STYLE=\"width:95%;\"><HR STYLE=\"width:98%;\">";
    
    echo "<B STYLE=\"font-size:1.2em;\">Dates:</B><BR>";
    echo "<INPUT NAME=all_dates TYPE=CHECKBOX CHECKED> All dates and election cycles<BR STYLE=\"line-height:190%;\">";
    echo "<B>Date range</B><BR> <INPUT TYPE=TEXT NAME=start_date VALUE=\"1/1/1980\" STYLE=\"width:64px;\"> - <INPUT TYPE=TEXT NAME=end_date VALUE=\"" . date ("n/j/Y") . "\" STYLE=\"width:64px;\">";
    echo "<BR STYLE=\"line-height:190%;\">";

    echo "<B>Election Cycles:</B><BR>";
    fill_election_cycles ();
    echo "<P>";

    echo "<P><INPUT ID=search TYPE=SUBMIT VALUE=\"Search\"></P>";

    echo "</FORM><P>";

    echo "<SCRIPT type=text/javascript>";
    echo "var candidates = [{$js_candidates}\"\"];";
    echo "var propositions = [{$js_propositions}\"\"];";
    echo "</SCRIPT>";
  }


  function fill_state_list () {
    echo "<OPTION VALUE=\"ALL\" SELECTED>All states</OPTION>";
    $result = my_query ("SELECT StateName, StateCode FROM smry_states WHERE IsState = 1 ORDER BY StateName");
    while ($row = $result->fetch_assoc()) {
      echo "<OPTION VALUE={$row["StateCode"]}>{$row["StateName"]}</OPTION>";
    }
  }


  function fill_candidate_names () {
    $javascript_array = "";
    $result = my_query ("SELECT RecipientCandidateNameNormalized FROM smry_candidates ORDER BY RecipientCandidateNameNormalized");
    while ($row = $result->fetch_assoc()) {
      echo "<OPTION>{$row["RecipientCandidateNameNormalized"]}</OPTION>";
      $javascript_array .= "\"" . str_replace ("\"", "", $row["RecipientCandidateNameNormalized"]) . "\",";
    }
    return $javascript_array;
  }


  function fill_offices_sought () {
    $result = my_query ("SELECT DISTINCT RecipientCandidateOffice FROM smry_offices ORDER BY RecipientCandidateOffice");
    while ($row = $result->fetch_assoc()) {
      echo "<OPTION>{$row["RecipientCandidateOffice"]}</OPTION>";
    }
  }


  function fill_elections () {
    $result = my_query ("SELECT DISTINCT Election FROM smry_propositions ORDER BY Election DESC");
    while ($row = $result->fetch_assoc()) {
      echo "<OPTION VALUE={$row["Election"]}>" . date ("M j, Y", strtotime ($row["Election"])) . "</OPTION>";
    }
  }


  function fill_propositions () {
    $javascript_array = "";
    $result = my_query ("SELECT DISTINCT Target FROM smry_propositions ORDER BY Election DESC, Target");
    while ($row = $result->fetch_assoc()) {
      echo "<OPTION VALUE=\"{$row["Target"]}\">{$row["Target"]}</OPTION>";
      $javascript_array .= "\"" . str_replace ("\"", "'", $row["Target"]) . "\",";
    }
    return $javascript_array;
  }


  function fill_election_cycles () {
    $result = my_query ("SELECT ElectionCycle FROM smry_cycles ORDER BY ElectionCycle DESC");
    while ($row = $result->fetch_assoc()) {
      $cycle_start = $row["ElectionCycle"];
      $cycle_end = $cycle_start + 1;
      echo "<div class=\"year-check\">";
      echo "<input type=\"checkbox\" id=\"y{$cycle_start}\">";
      echo "<label for=\"y{$cycle_start}\">$cycle_start-$cycle_end</label>";
      echo "</div>";
    }
  }
?>