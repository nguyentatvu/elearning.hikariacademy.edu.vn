<style>
    .category {
        margin-bottom: 30px;
    }

    .category:last-child {
        margin-bottom: 0;
    }

    .category-header {
        background: #ecf0f1;
        padding: 12px 15px;
        margin-bottom: 20px;
        border-left: 4px solid #3498db;
        font-weight: 500;
        color: #2c3e50;
        border-radius: 0 4px 4px 0;
    }

    .question {
        margin-bottom: 25px;
        padding: 20px;
        border: 1px solid #e1e8ed;
        border-radius: 8px;
        background: #fafbfc;
        transition: all 0.3s ease;
    }

    .question:hover {
        border-color: #3498db;
        box-shadow: 0 2px 8px rgba(52, 152, 219, 0.1);
    }
</style>
<div class="category">
    @foreach ($category_questions as $question)
        <div class="question">
            @component('client.components.tokutei-test.question',
                ['record' => $question,
                'test_structure' => $test_structure,
                'acc_score' => $acc_score])
            @endcomponent
        </div>
    @endforeach
</div>