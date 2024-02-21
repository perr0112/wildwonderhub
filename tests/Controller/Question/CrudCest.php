<?php

namespace App\Tests\Controller\Question;

use App\Entity\Question;
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

    public function TestCreatePost(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userBasic);

        $I->amOnPage('/forum');
        $I->click('Créer un post');
        $I->seeCurrentRouteIs('app_question_new');
        $I->fillField('question[title]', 'Titre test de création');
        $I->fillField('question[description]', 'Description (test) de création de post');
        $I->click('Créer le post');
        $I->see('Votre post a bien été créé!');
        $I->seeCurrentRouteIs('app_user_questions');
        $I->see('Titre test de création');
    }


    public function TestEditPost(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userBasic);

        $I->amOnPage('/questions/me');
        $I->see($this->question->getTitle());
        $I->amOnPage('/question/' . $this->question->getId());
        $I->seeResponseCodeIs(200);
        $I->see($this->question->getDescription());
        $I->click('Modifier');
        $I->seeCurrentRouteIs('app_question_edit', ['id' => $this->question->getId()]);
        $I->fillField('question[title]', 'Post modifié CRUD');
        $I->fillField('question[description]', 'Description (test) de modification de post');
        $I->click('Modifier le post');
        $I->see('Ce post a été modifié avec succès!');
        $I->seeCurrentRouteIs('app_question_show', ['id' => $this->question->getId()]);
        $I->amOnPage('/question/' . $this->question->getId());
        $I->seeResponseCodeIs(200);
        $I->see('Post modifié CRUD');
    }

    public function TestDeletePost(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userBasic);

        $I->amOnPage('/question/' . $this->question->getId() . '/edit');
        $I->click('button.btn.button-danger.open-modal');
        $I->see('Êtes-vous sûr de vouloir supprimer ce post?');
        $I->click('button#delete_delete');
        $I->see('Ce post a bien été supprimé!');
        $I->seeCurrentRouteIs('app_user_questions');
        $I->dontSee($this->question->getTitle());
    }

    public function TestUserCantEditPostOfOtherUser(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userBasic);

        $questionAdmin = QuestionFactory::createOne(
            [
                'title' => 'Post créé CRUD',
                'description' => 'Description (test) de création de post',
                'author' => $this->userAdmin,
            ]
        );
        $I->amOnPage('/question/' . $questionAdmin->object()->getId() . '/edit');
        $I->see('Vous n\'avez pas les droits pour modifier ce post.');
    }

}
