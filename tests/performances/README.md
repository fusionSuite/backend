# Performances tests

In this folder, there are scripts for use with k6 (https://k6.io/).

The goal is to have scripts testing the performances of the backend in queries charge.
Needs too test with many data in the backend.

# Run

The command to run the script is:

```
k6 run --vus 10 --duration 30s file.js
```

