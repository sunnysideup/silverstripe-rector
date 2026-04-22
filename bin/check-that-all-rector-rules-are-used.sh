find ./src/Rector -type f -name '*.php' | while read -r file; do
    className=$(basename "$file" .php)

    if ! grep -R --quiet --fixed-strings "$className" ./config; then
        echo "$className"
    fi
done