<?php

declare(strict_types=1);

namespace App\Repositories;

use App\{
    User,
    Banner,
    CoinRechargePackage,
    Comment,
    LmsSeriesCombo,
    LmsContent,
    LmsStudentView,
    LmsTest,
    LmsExam,
    LmsSeriesTeacher,
    LmsSeries,
    Flashcard,
    PaymentMethod,
    Payment,
    WeeklyLeaderboard,
    JapaneseWritingPractice,
    HiraganaWritingPractice,
    KanjiWritingPractice,
    Pronunciation,
    PronunciationDetail,
    Intonation,
    QuizResultfinish,
    Roadmap,
    UserRoadmap
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
        $this->app->bind(CoinRechargePackageRepository::class, function () {
            return new CoinRechargePackageRepository(new CoinRechargePackage());
        });
        $this->app->bind(PaymentRepository::class, function () {
            return new PaymentRepository(new Payment());
        });
        $this->app->bind(WeeklyLeaderboardRepository::class, function () {
            return new WeeklyLeaderboardRepository(new WeeklyLeaderboard());
        });
        $this->app->bind(HandwritingRepository::class, function () {
            return new HandwritingRepository(new JapaneseWritingPractice);
        });
        $this->app->bind(HiraganaWritingPracticeRepository::class, function () {
            return new HiraganaWritingPracticeRepository(new HiraganaWritingPractice);
        });
        $this->app->bind(KanjiWritingPracticeRepository::class, function () {
            return new KanjiWritingPracticeRepository(new KanjiWritingPractice);
        });
        $this->app->bind(PronunciationRepository::class, function () {
            return new PronunciationRepository(new Pronunciation);
        });
        $this->app->bind(PronunciationDetailRepository::class, function () {
            return new PronunciationDetailRepository(new PronunciationDetail);
        });
        $this->app->bind(IntonationRepository::class, function () {
            return new IntonationRepository(new Intonation);
        });
        $this->app->bind(CommentRepository::class, function () {
            return new CommentRepository(new Comment);
        });
        $this->app->bind(QuizResultFinishRepository::class, function () {
            return new QuizResultFinishRepository(new QuizResultfinish);
        });
        $this->app->bind(UserRoadmapRepository::class, function () {
            return new UserRoadmapRepository(new UserRoadmap);
        });
        $this->app->bind(RoadmapRepository::class, function () {
            return new RoadmapRepository(new Roadmap);
        });
    }
}
