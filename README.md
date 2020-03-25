# CLI PHP Script to add Matomo Goals

## Usage
```
php ./matomo_add_goals.php --goal_names_file=GOAL_NAMES_FILE.tsv --matomo_url="https://matomo-url.de/index.php" --id_site=ID_SITE --auth_token=AUTH_TOKEN
```
## Created matomo goals
Every created matomo goal will be pre-set with "matchAttribute=manually"
Hence you need to use
```
_paq.push(['trackGoal', GOAL-ID]);
```
in your Matomo-JS-Snippet to track these goals.

## Output  
This tool outputs a JSON string with key-value pairs
```
"Content partern name":"_paq.push(['trackGoal', GOAL-ID]);"
```
