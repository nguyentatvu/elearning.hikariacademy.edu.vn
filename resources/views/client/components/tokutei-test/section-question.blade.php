
<style>
    .section {
        margin-bottom: 40px;
        margin-right: 20px;
        margin-left: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .section-header {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-number {
        background: rgba(255, 255, 255, 0.2);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .section-content {
        padding: 20px;
    }
</style>
<div class="section">
    <div class="section-header">
        <div class="section-number">{{ $section_key }}</div>
        <span>{{ $test_structure['section'][$section_key]['label'] }}</span>
    </div>
    <div class="section-content">
        @foreach ($section_questions as $category_key => $category_questions)
            @component('client.components.tokutei-test.category-question',
                ['section_key' => $section_key,
                'category_key' => $category_key,
                'category_questions' => $category_questions,
                'test_structure' => $test_structure,
                'acc_score' => $acc_score])
            @endcomponent
        @endforeach
    </div>
</div>