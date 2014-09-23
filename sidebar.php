<?php
  # Search form HTML

  function build_sidebar_form () {
    echo "<FORM>";

    echo "<B>CONTRIBUTOR</B><BR>";
    echo "Contributor name contains:<BR><INPUT TYPE=TEXT NAME=contributor STYLE=\"width:95%;\"><BR>";
    echo "Contributor Location:<BR><SELECT NAME=location STYLE=\"width:95%;\"><OPTION VALUE=\"\">-- Any location</OPTION>";
    fill_state_list ();
    echo "</SELECT><P>";

    echo "<B>CANDIDATES</B><BR>";
    echo "<INPUT TYPE=RADIO NAME=candidates VALUE=0 CHECKED> All candidates<BR>";
    echo "<INPUT TYPE=RADIO NAME=candidates VALUE=1> Search candidates:<BR><INPUT TYPE=TEXT NAME=search_candidates STYLE=\"width:95%;\"><BR>";
    echo "<SELECT NAME=candidates STYLE=\"width:95%;\">";
    fill_candidate_names ();
    echo "</SELECT><BR>";
    echo "<INPUT TYPE=RADIO NAME=candidates VALUE=2> Office sought<BR><SELECT NAME=office STYLE=\"width:95%;\">";
    fill_offices_sought ();
    echo "</SELECT><P>";

    echo "<B>BALLOT MEASURES</B><BR>";
    echo "<INPUT TYPE=RADIO NAME=measures VALUE=0 CHECKED> Select election<BR><SELECT NAME=elections STYLE=\"width:95%;\">";
    fill_elections ();
    echo "</SELECT><BR>";
    echo "<INPUT TYPE=RADIO NAME=measures VALUE=1> Select proposition<BR><SELECT NAME=propositions STYLE=\"width:95%;\">";
    fill_propositions ();
    echo "</SELECT><BR>";
    echo "Support & Oppose<BR>";
    echo "<INPUT TYPE=CHECKBOX CHECKED> Support<BR>";
    echo "<INPUT TYPE=CHECKBOX CHECKED> Oppose<BR>";
    echo "<INPUT TYPE=CHECKBOX> Exclude contributions between allied committees<P>";

    echo "<B>COMMITTES</B><BR>";
    echo "Committee name contains<BR><INPUT TYPE=TEXT NAME=committee STYLE=\"width:95%;\"><P>";
    
    echo "<B>DATE</B><BR>";
    echo "<INPUT TYPE=RADIO NAME=dates VALUE=0 CHECKED> All dates and cycles<BR>";
    echo "<INPUT TYPE=RADIO NAME=dates VALUE=1> Date range<BR> <INPUT TYPE=TEXT NAME=start_date STYLE=\"width:60px;\"> - <INPUT TYPE=TEXT NAME=end_date STYLE=\"width:60px;\"><P>";

    echo "<B>ELECTION CYCLES</B><BR>";
    fill_election_cycles ();
    echo "<P>";

    echo "<INPUT TYPE=SUBMIT VALUE='Search'>";

    echo "</FORM><P>";
  }


  function fill_state_list () {
    $result = my_query ("SELECT StateName, StateCode FROM smry_states WHERE IsState = 1 ORDER BY StateName");
    while ($row = $result->fetch_assoc()) {
      echo "<OPTION VALUE={$row["StateCode"]}>{$row["StateName"]}</OPTION>";
    }
  }


  function fill_candidate_names () {
    $result = my_query ("SELECT RecipientCandidateNameNormalized FROM smry_candidates ORDER BY RecipientCandidateNameNormalized");
    while ($row = $result->fetch_assoc()) {
      echo "<OPTION>{$row["RecipientCandidateNameNormalized"]}</OPTION>";
    }
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
    $result = my_query ("SELECT * FROM smry_propositions ORDER BY Target, Election DESC");
    while ($row = $result->fetch_assoc()) {
      echo "<OPTION VALUE={$row["Election"]}>{$row["Target"]} (" . date ("M j, Y", strtotime ($row["Election"])) . ")</OPTION>";
    }
  }


  function fill_election_cycles () {
    $default_cycle = "CHECKED";
    $result = my_query ("SELECT ElectionCycle FROM smry_cycles ORDER BY ElectionCycle DESC");
    while ($row = $result->fetch_assoc()) {
      echo "<INPUT TYPE=CHECKBOX {$default_cycle}> {$row["ElectionCycle"]}<BR>";
      $default_cycle = "";
    }
  }
?>