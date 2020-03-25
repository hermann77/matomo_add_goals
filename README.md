CLI PHP Script to add Matomo Goals 

Every goal will be pre-set with "matchAttribute=manually"
Hence you need to use 
_paq.push(['trackGoal', GOAL-ID]);
to track these goals
 

Usage: php ./matomo_add_goals.php --goal_names_file=GOAL_NAMES_FILE.tsv --matomo_url="https://matomo-url.de/index.php" --id_site=ID_SITE --auth_token=AUTH_TOKEN
  
Outputs a JSON string with key-value pairs
"Content partern name":"_paq.push(['trackGoal', GOAL-ID]);"