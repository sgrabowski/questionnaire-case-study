# Missing Pieces

## Persistence

### Possible approaches
#1: Create mappings for the domain entities and store them directly into the DB for reading / writing
- easiest solution
- may require some on-the-fly transformations to display or edit a questionnaire

#2: Same as #1 but also create projections for reading purposes
- more maintenance
- easier reads - the data would already be in the format close to the DTO

#3: Same as #2 but the Questionnaire itself (not the builder) is event sourced
- even more maintenance
- questionnaire could be resumed at any point of time without a problem
- questionnaire could be fixed and re-run if necessary

## API to show the questionnaire, receive the answers, and show recommendations
### Requirements

- returns all questions and answers
- marks answers which are conditional

### Possible approaches
#1: Questionnaire is always sent and received as whole
- The customer will always have the whole context and know how much time he will spend on the questionnaire
- It's more challenging to finish the questionnaire early without involving frontend logic
- Handling errors and resuming the questionnaire in case of one requires more thoughtful planning

#2: Questionnaire is displayed and filled one question at a time
- Customer sees only 1 question at a time, doesn't know how many questions to expect
- DTOs structure is simplified and the frontend doesn't care much about the flow or nested questions
- Errors can be communicated sooner and are almost always related to the visible question

### Proposed DTOs, approach #1
#### GET /api/questionnaire/{id}
```json
{
  "id": "1c410562-bc45-4db2-8e7a-aefcf6cef82d",
  "questions": [
    {
      "id": "1a39d459-b8db-468b-8d02-d06da09e34b8",
      "number": "1",
      "text": "Do you have difficulty getting or maintaining an erection?",
      "answers": [
        {
          "id": "0a36ebae-6b99-4d63-b08e-9b5a9e99b97f",
          "text": "Yes"
        },
        {
          "id": "b32a07bc-6872-4d1f-8b2b-e18f0e634f08",
          "text": "No"
        }
      ],
      "subquestions": []
    },
    {
      "id": "...",
      "number": "2",
      "text": "Have you tried any of the following treatments before?",
      "answers": [
        {
          "id": "...",
          "text": "Viagra or Sildenafil",
          "next": "c0d332e4-6c3f-4158-91e7-38da64a0e92b"
        },
        {
          "id": "...",
          "text": "Cialis or Tadalafil",
          "next": "d8f83e70-e3d3-491c-ac72-4de73d281b81"
        }
      ],
      "subquestions": [
        {
          "id": "c0d332e4-6c3f-4158-91e7-38da64a0e92b",
          "number": "2a",
          "text": "Was the Viagra or Sildenafil product you tried before effective?",
          "answers": [
            ...
          ]
        }
      ]
    }
  ]
}
```

#### POST /api/questionnaire/{id}
```json
{
  "answers": [
    "0a36ebae-6b99-4d63-b08e-9b5a9e99b97f",
    "b32a07bc-6872-4d1f-8b2b-e18f0e634f08",
    "..."
  ]
}
```
#### Response
```json
{
  "recommendations": [
    {
      "id": "1c410562-bc45-4db2-8e7a-aefcf6cef82d",
      "name": "Sildenafil 50mg"
    },
    {
      "id": "56196557-d62b-4deb-8376-33e57d1fdb2e",
      "name": "Tadalafil 10mg"
    }
  ]
}
```

## Admin panel to manage questionnaires
### Requirements

- add / remove questionnaires
- add / remove / alter outcome of existing questions

### Possible approaches
#1: Bespoke UI where the admin can drag&drop questionnaire elements and edit them on the fly. Afterwards the whole questionnaire is replaced with the resulting one.
- Convenient for the admins
- More difficult to develop and maintain

#2: Simple generated UI (sonata or something similar) where everything is form-based, even the order of the questions.
- Easier and faster to create
- Less readable to edit and create. More manual labor necessary.