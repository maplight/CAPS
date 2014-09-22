<?php
  # Search form HTML

  function build_sidebar_form () {
    echo "<FORM>";

    echo "CONTRIBUTOR<BR>";
    echo "<INPUT TYPE=TEXT><BR>";
    echo "Contributor Location:<BR><SELECT></SELECT><P>";

    echo "<INPUT TYPE=CHECKBOX> CANDIDATES<BR>";
    echo "<INPUT TYPE=RADIO NAME=candidates> All candidates<BR>";
    echo "<INPUT TYPE=RADIO NAME=candidates> These candidates<BR> <INPUT TYPE=TEXT><BR><SELECT>";
    fill_candidate_names ();
    echo "</SELECT><BR>";
    echo "<INPUT TYPE=RADIO NAME=candidates> Office sought<BR> <SELECT></SELECT><P>";

    echo "<INPUT TYPE=CHECKBOX> BALLOT MEASURES<BR>";
    echo "Elections<BR> <SELECT></SELECT><BR>";
    echo "Propositions<BR> <SELECT></SELECT><BR>";
    echo "Support & Oppose<BR> <SELECT></SELECT><BR>";
    echo "<INPUT TYPE=CHECKBOX> Exclude contributions between allied committees<P>";

    echo "<INPUT TYPE=CHECKBOX> COMMITTES<BR>";
    echo "These committees<BR> <INPUT TYPE=TEXT><BR><SELECT></SELECT><P>";
    
    echo "DATE<BR>";
    echo "<INPUT TYPE=RADIO NAME=dates> All dates and election cycles<BR>";
    echo "<INPUT TYPE=RADIO NAME=dates> Date range<BR> <INPUT TYPE=TEXT> <INPUT TYPE=TEXT><P>";

    echo "ELECTION CYCLES<BR>";
    echo "<INPUT TYPE=CHECKBOX> xxxx<P>";

    echo "<INPUT TYPE=SUBMIT VALUE='Search'>";

    echo "</FORM><P>";
  }


  function fill_candidate_names () {
    $result = my_query ("SELECT RecipientCandidateNameNormalized FROM sub_candidate_names ORDER BY RecipientCandidateNameNormalized");
    while ($row = $result->fetch_assoc()) {
      echo "<OPTION>{$row["RecipientCandidateNameNormalized"]}</OPTION>";
    }
  }
?>