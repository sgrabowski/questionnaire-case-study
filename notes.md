# Notes and considerations
### Outcome Processing
The switch-case structure in processOutcome method handles different types of outcomes.

Each case method (e.g., handleExcludeAllAndFinish, handleExcludeCategory, etc.) needs
clear boundaries and error checks, for example,
ensuring that it correctly processes or skips over unexpected values or nulls.

### UUIDs
The system heavily relies on UUIDs for identifying questions and answers. 
Ensuring that these IDs are unique and correctly referenced across different
parts of the system is crucial. 

Alternative approach would be to create bespoke ID objects for each entity

### Readability and performance
As questions and logic layers add up, the performance might be a concern,
especially with the nested loops and array manipulations in buildMap and findNextQuestionId methods.
It would be beneficial to ensure that these functions operate efficiently even with a large data set.

### Etc
Other notes are scattered through the codebase as TODOs for better context and convenience, I would not normally put them there.

### Provided data
In question 3 "Do you have, or have you ever had, any heart or neurological conditions?" the expected results should be swapped I think.

Currently products are excluded only when choosing "No" as an answer.