<?php

namespace App\Tests\Helper\Questionnaire;

use App\Questionnaire\Domain\Entity\Answer;
use App\Questionnaire\Domain\Entity\Question;
use App\Questionnaire\Domain\Entity\QuestionnaireBuilder;
use App\Questionnaire\Domain\Value\AnswerOutcome\Combined;
use App\Questionnaire\Domain\Value\AnswerOutcome\ExcludeAllAndFinish;
use App\Questionnaire\Domain\Value\AnswerOutcome\ExcludeCategory;
use App\Questionnaire\Domain\Value\AnswerOutcome\NextQuestion;
use App\Questionnaire\Domain\Value\AnswerOutcome\Proceed;
use App\Questionnaire\Domain\Value\AnswerOutcome\Recommend;
use App\Questionnaire\Domain\Value\Recommendation;
use Ramsey\Uuid\Uuid;

class DefaultQuestionnaireBuilder
{
    public const SILDENAFIL_CATEGORY_ID = '1c410562-bc45-4db2-8e7a-aefcf6cef82d';
    public const TADALAFIL_CATEGORY_ID = 'e4021f18-e1e5-47d8-9db1-8e2c3d98293b';
    public const SILDENAFIL_50_ID = '6b8556dc-5ccd-4e25-8af6-3ed198f826a6';
    public const SILDENAFIL_100_ID = '1326c80c-4b17-4e22-8776-2d8b8762f3b9';
    public const TADALAFIL_10_ID = '56196557-d62b-4deb-8376-33e57d1fdb2e';
    public const TADALAFIL_20_ID = '4704458b-0f0b-4def-ba3a-e170821644f4';

    public const QUESTION_1_ID = '1a39d459-b8db-468b-8d02-d06da09e34b8';
    public const QUESTION_2_ID = 'ed46e42a-6c1e-43c3-a43c-238eb4ceb382';
    public const QUESTION_2A_ID = 'c0d332e4-6c3f-4158-91e7-38da64a0e92b';
    public const QUESTION_2B_ID = 'd8f83e70-e3d3-491c-ac72-4de73d281b81';
    public const QUESTION_2C_ID = 'c29e5317-4f93-493e-a593-13bcb0cbd2bf';
    public const QUESTION_3_ID = '1603b79f-0731-42fd-a03b-3c94a0893fc2';
    public const QUESTION_4_ID = 'd6a3c214-83b8-4143-ad32-182da459a1c3';
    public const QUESTION_5_ID = 'a3cc1d26-c327-47e4-976a-ad9e88d82e5e';

    // UUIDs for answers
    public const ANSWER_1_YES_ID = '0a36ebae-6b99-4d63-b08e-9b5a9e99b97f';
    public const ANSWER_1_NO_ID = 'b32a07bc-6872-4d1f-8b2b-e18f0e634f08';
    public const ANSWER_2_VIAGRA_ID = '3e7c9d4a-d511-42a9-861f-1c3b8b36bfa1';
    public const ANSWER_2_CIALIS_ID = '847fd17d-6bdc-45e8-a7fe-c676c3b2a01f';
    public const ANSWER_2_BOTH_ID = '7e1f8ce2-84ec-4f35-9283-728ee6d31081';
    public const ANSWER_2_NONE_ID = '1d487316-3f5a-49db-9614-9f501d36f0c7';
    public const ANSWER_2A_YES_ID = 'cafe775f-376e-4385-82d9-455abdd7a6db';
    public const ANSWER_2A_NO_ID = 'e5c76e7e-b639-45c1-8ad3-2f94ad1ecc32';
    public const ANSWER_2B_YES_ID = '2b142950-7837-45af-baf5-7555a85d58d2';
    public const ANSWER_2B_NO_ID = 'f87d0a1d-b310-4f8b-a56e-18c5646ac611';
    public const ANSWER_2C_VIAGRA_ID = '548f2687-883e-4711-93e9-3479a798d7c4';
    public const ANSWER_2C_CIALIS_ID = '15cd6bf7-e227-45ae-a38e-a4075788f54a';
    public const ANSWER_2C_NONE_ID = '67e3c6b2-ee74-4b8b-b200-1f442de9878a';
    public const ANSWER_3_YES_ID = 'a6f81e4f-9c5b-475f-80c0-272d9d674888';
    public const ANSWER_3_NO_ID = '06bd9c45-927b-413c-9d8b-f2045b55c531';
    public const ANSWER_4_LIVER_ID = '2217bc8c-8269-4de8-93be-640506740037';
    public const ANSWER_4_NITRATES_ID = 'f673142b-aa4a-4c59-bb5b-0b0d8b77b509';
    public const ANSWER_4_BP_ID = '9243ac6b-06bf-4006-818a-26f2031c3cd9';
    public const ANSWER_4_PEYRONIE_ID = '43c1e938-c384-4565-a8cd-9b5069c3c879';
    public const ANSWER_4_NONE_ID = 'cf265345-ae8d-4e55-bb67-a19c2ff50528';
    public const ANSWER_5_ALPHA_ID = 'af402cc4-620b-4577-8aed-6c75df8dadc1';
    public const ANSWER_5_GCS_ID = '72ee2e56-8c25-4b27-aa64-1c54b0812e14';
    public const ANSWER_5_HIV_ID = '41a3acca-925f-4532-ba6c-8997b2a5f18d';
    public const ANSWER_5_CIMETIDINE_ID = '9a7b51a5-5f3a-465c-aade-7c47bcc5739d';
    public const ANSWER_5_NONE_ID = '93c8b086-daef-4684-bb9d-10c9d3980d86';

    public static function create(): QuestionnaireBuilder
    {
        $builder = new QuestionnaireBuilder(Uuid::uuid4());

        // Question 1
        $q1 = new Question(Uuid::fromString(self::QUESTION_1_ID), 'Do you have difficulty getting or maintaining an erection?', []);
        $q1a1 = new Answer(Uuid::fromString(self::ANSWER_1_YES_ID), 'Yes', new Proceed());
        $q1a2 = new Answer(Uuid::fromString(self::ANSWER_1_NO_ID), 'No', new ExcludeAllAndFinish());
        $q1->addAnswer($q1a1);
        $q1->addAnswer($q1a2);

        // Question 2
        $q2 = new Question(Uuid::fromString(self::QUESTION_2_ID), 'Have you tried any of the following treatments before?', []);
        $q2aId = Uuid::fromString(self::QUESTION_2A_ID);
        $q2a1 = new Answer(Uuid::fromString(self::ANSWER_2_VIAGRA_ID), 'Viagra or Sildenafil', new NextQuestion($q2aId));
        $q2bId = Uuid::fromString(self::QUESTION_2B_ID);
        $q2a2 = new Answer(Uuid::fromString(self::ANSWER_2_CIALIS_ID), 'Cialis or Tadalafil', new NextQuestion($q2bId));
        $q2cId = Uuid::fromString(self::QUESTION_2C_ID);
        $q2a3 = new Answer(Uuid::fromString(self::ANSWER_2_BOTH_ID), 'Both', new NextQuestion($q2cId));
        $q2a4 = new Answer(Uuid::fromString(self::ANSWER_2_NONE_ID), 'None of the above', new Recommend([
            new Recommendation(Uuid::fromString(self::SILDENAFIL_50_ID), Uuid::fromString(self::SILDENAFIL_CATEGORY_ID)),
            new Recommendation(Uuid::fromString(self::TADALAFIL_10_ID), Uuid::fromString(self::TADALAFIL_CATEGORY_ID)),
        ]));
        $q2->addAnswer($q2a1);
        $q2->addAnswer($q2a2);
        $q2->addAnswer($q2a3);
        $q2->addAnswer($q2a4);

        // Subquestions for Question 2
        // Subquestion 2a
        $q2a = new Question(Uuid::fromString(self::QUESTION_2A_ID), 'Was the Viagra or Sildenafil product you tried before effective?', []);
        // Subquestion 2b
        $q2b = new Question(Uuid::fromString(self::QUESTION_2B_ID), 'Was the Cialis or Tadalafil product you tried before effective?', []);
        // Subquestion 2c
        $q2c = new Question(Uuid::fromString(self::QUESTION_2C_ID), 'Which is your preferred treatment?', []);

        // Update Subquestion 2a
        $q2a->addAnswer(new Answer(Uuid::fromString(self::ANSWER_2A_YES_ID), 'Yes', new Combined([
            new ExcludeCategory(Uuid::fromString(self::TADALAFIL_CATEGORY_ID)),
            new Recommend([
                new Recommendation(Uuid::fromString(self::SILDENAFIL_50_ID), Uuid::fromString(self::SILDENAFIL_CATEGORY_ID)),
            ]),
        ])));
        $q2a->addAnswer(new Answer(Uuid::fromString(self::ANSWER_2A_NO_ID), 'No', new Combined([
            new ExcludeCategory(Uuid::fromString(self::SILDENAFIL_CATEGORY_ID)),
            new Recommend([
                new Recommendation(Uuid::fromString(self::TADALAFIL_20_ID), Uuid::fromString(self::TADALAFIL_CATEGORY_ID)),
            ]),
        ])));

        // Update Subquestion 2b
        $q2b->addAnswer(new Answer(Uuid::fromString(self::ANSWER_2B_YES_ID), 'Yes', new Combined([
            new ExcludeCategory(Uuid::fromString(self::SILDENAFIL_CATEGORY_ID)),
            new Recommend([
                new Recommendation(Uuid::fromString(self::TADALAFIL_10_ID), Uuid::fromString(self::TADALAFIL_CATEGORY_ID)),
            ]),
        ])));
        $q2b->addAnswer(new Answer(Uuid::fromString(self::ANSWER_2B_NO_ID), 'No', new Combined([
            new ExcludeCategory(Uuid::fromString(self::TADALAFIL_CATEGORY_ID)),
            new Recommend([
                new Recommendation(Uuid::fromString(self::SILDENAFIL_100_ID), Uuid::fromString(self::SILDENAFIL_CATEGORY_ID)),
            ]),
        ])));

        // Update Subquestion 2c
        $q2c->addAnswer(new Answer(Uuid::fromString(self::ANSWER_2C_VIAGRA_ID), 'Viagra or Sildenafil', new Combined([
            new ExcludeCategory(Uuid::fromString(self::TADALAFIL_CATEGORY_ID)),
            new Recommend([
                new Recommendation(Uuid::fromString(self::SILDENAFIL_100_ID), Uuid::fromString(self::SILDENAFIL_CATEGORY_ID)),
            ]),
        ])));
        $q2c->addAnswer(new Answer(Uuid::fromString(self::ANSWER_2C_CIALIS_ID), 'Cialis or Tadalafil', new Combined([
            new ExcludeCategory(Uuid::fromString(self::SILDENAFIL_CATEGORY_ID)),
            new Recommend([
                new Recommendation(Uuid::fromString(self::TADALAFIL_20_ID), Uuid::fromString(self::TADALAFIL_CATEGORY_ID)),
            ]),
        ])));
        $q2c->addAnswer(new Answer(Uuid::fromString(self::ANSWER_2C_NONE_ID), 'None of the above', new Combined([
            new Recommend([
                new Recommendation(Uuid::fromString(self::SILDENAFIL_100_ID)),
                new Recommendation(Uuid::fromString(self::TADALAFIL_20_ID)),
            ]),
        ])));

        // Question 3
        $q3 = new Question(Uuid::fromString(self::QUESTION_3_ID), 'Do you have, or have you ever had, any heart or neurological conditions?', []);
        $q3a1 = new Answer(Uuid::fromString(self::ANSWER_3_YES_ID), 'Yes', new Proceed());
        $q3a2 = new Answer(Uuid::fromString(self::ANSWER_3_NO_ID), 'No', new ExcludeAllAndFinish());
        $q3->addAnswer($q3a1);
        $q3->addAnswer($q3a2);

        // Question 4
        $q4 = new Question(Uuid::fromString(self::QUESTION_4_ID), 'Do any of the listed medical conditions apply to you?', []);
        $q4a1 = new Answer(Uuid::fromString(self::ANSWER_4_LIVER_ID), 'Significant liver problems (such as cirrhosis of the liver) or kidney problems', new ExcludeAllAndFinish());
        $q4a2 = new Answer(Uuid::fromString(self::ANSWER_4_NITRATES_ID), 'Currently prescribed GTN, Isosorbide mononitrate, Isosorbide dinitrate, Nicorandil (nitrates) or Rectogesic ointment', new ExcludeAllAndFinish());
        $q4a3 = new Answer(Uuid::fromString(self::ANSWER_4_BP_ID), 'Abnormal blood pressure (lower than 90/50 mmHg or higher than 160/90 mmHg)', new ExcludeAllAndFinish());
        $q4a4 = new Answer(Uuid::fromString(self::ANSWER_4_PEYRONIE_ID), 'Condition affecting your penis (such as Peyronie\'s Disease, previous injuries or an inability to retract your foreskin)', new ExcludeAllAndFinish());
        $q4a5 = new Answer(Uuid::fromString(self::ANSWER_4_NONE_ID), 'I don\'t have any of these conditions', new Proceed());
        $q4->addAnswer($q4a1);
        $q4->addAnswer($q4a2);
        $q4->addAnswer($q4a3);
        $q4->addAnswer($q4a4);
        $q4->addAnswer($q4a5);

        // Question 5
        $q5 = new Question(Uuid::fromString(self::QUESTION_5_ID), 'Are you taking any of the following drugs?', []);
        $q5a1 = new Answer(Uuid::fromString(self::ANSWER_5_ALPHA_ID), 'Alpha-blocker medication such as Alfuzosin, Doxazosin, Tamsulosin, Prazosin, Terazosin or over-the-counter Flomax', new ExcludeAllAndFinish());
        $q5a2 = new Answer(Uuid::fromString(self::ANSWER_5_GCS_ID), 'Riociguat or other guanylate cyclase stimulators (for lung problems)', new ExcludeAllAndFinish());
        $q5a3 = new Answer(Uuid::fromString(self::ANSWER_5_HIV_ID), 'Saquinavir, Ritonavir or Indinavir (for HIV)', new ExcludeAllAndFinish());
        $q5a4 = new Answer(Uuid::fromString(self::ANSWER_5_CIMETIDINE_ID), 'Cimetidine (for heartburn)', new ExcludeAllAndFinish());
        $q5a5 = new Answer(Uuid::fromString(self::ANSWER_5_NONE_ID), 'I don\'t take any of these drugs', new Proceed());
        $q5->addAnswer($q5a1);
        $q5->addAnswer($q5a2);
        $q5->addAnswer($q5a3);
        $q5->addAnswer($q5a4);
        $q5->addAnswer($q5a5);

        // Add all questions to the builder
        $builder->addQuestion($q1);
        $builder->addQuestion($q2);
        $builder->addQuestion($q2a);
        $builder->addQuestion($q2b);
        $builder->addQuestion($q2c);
        $builder->addQuestion($q3);
        $builder->addQuestion($q4);
        $builder->addQuestion($q5);

        return $builder;
    }
}
