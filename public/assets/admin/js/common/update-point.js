/**
 * Calculate points for exercise
 * If score is greater than 100, reward points as 3
 * If score is greater than 80 and less than 100, reward points as 2
 * If score is greater than 65 and less than 80, reward points as 1
 */
function calculatePointsForExercise(score, totalScore) {
    const exerciseScore = (score / totalScore) * 100;
    if (exerciseScore == 100) {
        return 3;
    } else if (exerciseScore > 80) {
        return 2;
    } else if (exerciseScore > 65) {
        return 1;
    }

    return 0;
}