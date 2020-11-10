#!/bin/bash

cat << 'EOF' > ./.git/hooks/commit-message && chmod +x ./.git/hooks/commit-message
#!/bin/sh

COMMIT_FILE=$1
COMMIT_MSG=$(cat $1)
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
JIRA_ID=$(echo "$CURRENT_BRANCH" | grep -Eo "[A-Z0-9]{1,10}-?[A-Z0-9]+-\d+")

if [ ! -z "$JIRA_ID" ]; then
    echo "$JIRA_ID $COMMIT_MSG" > $COMMIT_FILE
        echo "JIRA ID '$JIRA_ID', matched in current branch name, prepended to commit message. (Use --no-verify to skip)"
fi
EOF