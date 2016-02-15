from __future__ import print_function

#from datetime import datetime
from urllib2 import urlopen

server = 'http://52.76.235.20'
paths = [
    '/issues/store_issue_urgency_score_across_projects',
    '/scheduled_tasks/calibrate_bb_milestones',
    '/dashboard/fetch_all_issues'
]

def lambda_handler(event, context):
    for path in paths:
        try:
            urlopen(server+path).read()
        except :
            print('Exception encountered')
            raise