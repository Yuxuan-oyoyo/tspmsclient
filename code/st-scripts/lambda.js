var http = require('http');

processRequest = function(thePath, context){
  options = {
    url :'52.76.235.20',
	path:thePath
  }
  console.log("Sending request to "+options.url+thePath)
  http.get(options, function(res) {
    console.log("Got response: " + res.statusCode);
	/*
    res.on("data", function(chunk) {
      console.log("BODY: " + chunk);
    });
	*/
    context.succeed();

  }).on('error', function(e) {
    console.log("Got error: " + e.message);
    context.done(null, 'FAILURE');
  });
  console.log('end request to ' +options.url+thePath);
}

exports.handler = function(event, context) {
  var path = [];
  var context = [];

  path[0] = '/scheduled_tasks/calibrate_bb_milestones';
  path[1] = '/dashboard/fetch_all_issues';
  path[2] = '/issues/store_issue_urgency_score_across_projects';
  for(var i=0;i<path.length;i++){
    //console.log("sending request");
    processRequest(path[i],context);
  }
}