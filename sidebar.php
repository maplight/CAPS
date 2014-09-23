<?php
  # Search form HTML

  function build_sidebar_form () {
    echo "<FORM>";

    echo "CONTRIBUTOR<BR>";
    echo "<INPUT TYPE=TEXT><BR>";
    echo "Contributor Location:<BR><SELECT>";
    fill_state_list ();
    echo "</SELECT><P>";

    echo "CANDIDATES<BR>";
    echo "<INPUT TYPE=RADIO NAME=candidates> All candidates<BR>";
    echo "<INPUT TYPE=RADIO NAME=candidates> These candidates<BR> <INPUT TYPE=TEXT><BR><SELECT>";
    fill_candidate_names ();
    echo "</SELECT><BR>";
    echo "<INPUT TYPE=RADIO NAME=candidates> Office sought<BR> <SELECT>";
    fill_offices_sought ();
    echo "</SELECT><P>";

    echo "BALLOT MEASURES<BR>";
    echo "<INPUT TYPE=RADIO NAME=measures> Elections<BR> <SELECT>";
    fill_elections ();
    echo "</SELECT><BR>";
    echo "<INPUT TYPE=RADIO NAME=measures> Propositions<BR> <SELECT></SELECT><BR>";
    echo "Support & Oppose<BR> <SELECT></SELECT><BR>";
    echo "<INPUT TYPE=CHECKBOX> Exclude contributions between allied committees<P>";

    echo "COMMITTES<BR>";
    echo "These committees<BR> <INPUT TYPE=TEXT><BR><SELECT></SELECT><P>";
    
    echo "DATE<BR>";
    echo "<INPUT TYPE=RADIO NAME=dates> All dates and election cycles<BR>";
    echo "<INPUT TYPE=RADIO NAME=dates> Date range<BR> <INPUT TYPE=TEXT> <INPUT TYPE=TEXT><P>";

    echo "ELECTION CYCLES<BR>";
    echo "<INPUT TYPE=CHECKBOX> xxxx<P>";

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
    $result = my_query ("SELECT Election FROM smry_elections ORDER BY Election DESC");
    while ($row = $result->fetch_assoc()) {
      echo "<OPTION VALUE={$row["Election"]}>" . date ("M j, Y", strtotime ($row["Election"])) . "</OPTION>";
    }
  }
?>