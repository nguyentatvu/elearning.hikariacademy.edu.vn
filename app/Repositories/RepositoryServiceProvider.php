<?php

declare(strict_types=1);

namespace App\Repositories;

use App\{
    User,
    Banner,
    CoinRechargePackage,
    LmsSeriesCombo,
    LmsContent,
    LmsStudentView,
    LmsTest,
    LmsExam,
    LmsSeriesTeacher,
    LmsSeries,
    Flashcard,
    PaymentMethod,
    MessageHistory,
    Conversation,
    Payment,
};

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /*
     * Register Service Providers
    */
    public function register()
    {
        $this->app->bind(UserRepository::class, function () {
            return new UserRepository(new User);
        });
        $this->app->bind(BannerRepository::class, function () {
            return new BannerRepository(new Banner);
        });
        $this->app->bind(LmsSeriesComboRepository::class, function () {
            return new LmsSeriesComboRepository(new LmsSeriesCombo);
        });
        $this->app->bind(LmsContentRepository::class, function () {
            return new LmsContentRepository(new LmsContent);
        });
        $this->app->bind(LmsStudentViewRepository::class, function () {
            return new LmsStudentViewRepository(new LmsStudentView);
        });
        $this->app->bind(LmsTestRepository::class, function () {
            return new LmsTestRepository(new LmsTest);
        });
        $this->app->bind(LmsExamRepository::class, function () {
            return new LmsExamRepository(new LmsExam);
        });
        $this->app->bind(LmsFlashcardRepository::class, function () {
            return new LmsFlashcardRepository(new Flashcard);
        });
        $this->app->bind(PaymentMethodRepository::class, function () {
            return new PaymentMethodRepository(new PaymentMethod);
        });
        $this->app->bind(LmsSeriesTeacherRepository::class, function () {
            return new LmsSeriesTeacherRepository(new LmsSeriesTeacher);
        });
        $this->app->bind(LmsSeriesRepository::class, function () {
            return new LmsSeriesRepository(new LmsSeries);
        });
        $this->app->bind(MessageHistoryRepository::class, function () {
            return new MessageHistoryRepository(new MessageHistory);
        });
        $this->app->bind(ConversationRepository::class, function () {
            return new ConversationRepository(new Conversation);
        });
        $this->app->bind(CoinRechargePackageRepository::class, function () {
            return new CoinRechargePackageRepository(new CoinRechargePackage());
        });
        $this->app->bind(PaymentRepository::class, function () {
            return new PaymentRepository(new Payment());
        });
    }
}
