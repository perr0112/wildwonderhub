<?php


namespace App\Tests\Controller\Answer;

use App\Entity\Question;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Tests\Support\ControllerTester;
use App\Tests\Support\UsersSetup;

class CrudCest
{
    use UsersSetup;

    private Question $question;
    public function _before(ControllerTester $I): void
    {
        $this->createUsers();

        // création d'un post
        $question = QuestionFactory::createOne(
            [
                'title' => 'Post créé CRUD',
                'description' => 'Description (test) de création de post',
                'isResolved' => false,
                'author' => $this->userBasic,
            ]
        );
        $this->question = $question->object();
    }

    public function TestCreateAnswerOnUnresolvedPost(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userBasic);

        $I->amOnPage('/question/' . $this->question->getId());
        $I->seeResponseCodeIs(200);
        $I->see($this->question->getTitle());

        // before answer
        $nbLikes = $this->question->countLikes();

        // fill answer[description] field and click on "Répondre"
        $I->fillField('answer[description]', 'Description (test) de création de réponse');
        $I->click('Répondre');
        $I->see('Votre réponse a bien été ajoutée!');
        $I->seeCurrentRouteIs('app_question_show', ['id' => $this->question->getId()]);

        // after answer
        $res = $nbLikes === 0 ? 'réponse' : 'réponses';
        $ans = $nbLikes + 1;
        $I->see($ans . ' ' . $res);
    }


    public function TestCreateAnswerOnResolvedPost(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userBasic);

        $I->amOnPage('/question/' . $this->question->getId());
        $I->seeResponseCodeIs(200);
        $I->see($this->question->getTitle());
        $I->click('Fermer le post');
        $I->see('Ce post a bien été clôturé!');
        $I->dontSee('Répondre');
    }


    public function TestEditAnswer(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userBasic);

        $I->amOnPage('/question/' . $this->question->getId());

        $answer = AnswerFactory::createOne(
            [
                'description' => 'Description (test) de création de réponse',
                'question' => $this->question,
                'author' => $this->userBasic,
            ]
        );

        $I->amOnPage('/answer/' . $answer->getId() . '/edit');
        $I->seeResponseCodeIs(200);
        $I->see($answer->getDescription());

        $I->fillField('answer[description]', 'Description (test) de modification de réponse');
        $I->click('Modifier la réponse');
        $I->see('La réponse a bien été modifiée.');
        $I->seeCurrentRouteIs('app_question_show', ['id' => $this->question->getId()]);
        $I->see('Description (test) de modification de réponse');
    }

    public function TestDeleteAnswer(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userBasic);

        $I->amOnPage('/question/' . $this->question->getId());

        $answer = AnswerFactory::createOne(
            [
                'description' => 'Description (test) de création de réponse',
                'question' => $this->question,
                'author' => $this->userBasic,
            ]
        );
        $I->amOnPage('/answer/' . $answer->getId() . '/edit');
        $I->seeResponseCodeIs(200);
        $I->see($answer->getDescription());

        $I->click('button.btn.button-danger.open-modal');
        $I->see('Êtes-vous sûr de vouloir supprimer cette réponse?');
        $I->click('button#delete_delete');
        $I->see('Cette réponse a bien été supprimée!');

        $I->seeCurrentRouteIs('app_question_show', ['id' => $this->question->getId()]);
        // $I->dontSee($answer->getDescription());
        $I->dontSee('Description (test) de création de réponse');
    }

    public function TestAnswerEditNoAuthorized(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userBasic);

        $I->amOnPage('/question/' . $this->question->getId());

        $answer = AnswerFactory::createOne(
            [
                'description' => 'Description (test) de création de réponse',
                'question' => $this->question,
                'author' => $this->userAdmin,
            ]
        );
        $I->amOnPage('/answer/' . $answer->getId() . '/edit');

        $I->see('Vous n\'avez pas les droits pour modifier cette réponse.');
        $I->seeCurrentRouteIs('app_forum');
    }

}
