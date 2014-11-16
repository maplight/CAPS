function filter_candidates_list() {
  $('#candidate_list').empty();

  $.each(candidates, function(key, value) {
    if (value != '') {
      if (value == 'Select candidate') {
        $('#candidate_list')
          .append($('<option></option>')
          .attr('value', value)
          .text(value)); 
      } else {
        if (value.indexOf($('#search_candidates').val().toUpperCase()) != -1) {
          $('#candidate_list')
            .append($('<option></option>')
            .attr('value', value)
            .text(value));
        }
      }
    }
  });
}


function filter_propositions_list() {
  $('#propositions_list').empty();

  $.each(propositions, function(key, value) {
    if (value.length == 2) {
      if (value[0].substring(0, 3) == 'ALL') {
        $('#propositions_list')
          .append($('<option></option>')
          .attr('value', value[0])
          .text(value[1]));
      } else {
        if (value[0].indexOf($('#search_propositions').val().toUpperCase()) != -1) {
          $('#propositions_list')
            .append($('<option></option>')
            .attr('value', value[0])
            .text(value[1]));
        }
      }
    } 
  });
}


function display_tooltip(event, tooltip_id) {
  switch (tooltip_id) {
    case 1: tip_text = 'Search contributions from all contributors.'; break;
    case 2: tip_text = 'Search contributions from a particular state.'; break;
    case 3: tip_text = 'Search contributions to candidate campaigns only.'; break;
    case 4: tip_text = 'Search contributions to a particular candidate\'s campaign(s).'; break;
    case 5: tip_text = 'Search contributions to all candidates running for a particular office.'; break;
    case 6: tip_text = 'Search contributions to committees formed to support or oppose ballot measures. Your results may return duplicate contributions if a contributor gave money to a committee supporting or opposing multiple ballot measures.'; break;
    case 7: tip_text = 'Search contributions to other committees, such as candidate office holder and legal defense committees.'; break;
    case 8: tip_text = 'Search contributions by the date range in which they were made.'; break;
    case 9: tip_text = 'This is the total amount received by number of contributions (does not include unitemized contributions). The table displays all contributions in the given search parameters, including both itemized contributions (of $100 or more) and unitemized contribution totals.'; break;
    case 10: tip_text = 'Download the search results as a CSV file.'; break;
    case 11: tip_text = 'Show more columns in the table for additional information on contributors.'; break;
  }

  if (window.event) {
    x = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
    y = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
  } else {
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }
  
  document.getElementById('tooltip_text').innerHTML = tip_text;
  tip = document.getElementById('tooltip');
  y = y - 20;
  x = x + 20;
  tip.style.top = y + 'px';
  tip.style.left = x + 'px';
  tip.style.display = 'block';
}