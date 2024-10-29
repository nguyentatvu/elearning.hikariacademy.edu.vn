<div class="modal fade bd-example-modal-lg certification-modal" id="certificationModal" tabindex="-1" role="dialog" aria-labelledby="certificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="panel-body" style="padding: 30px" class="chungchi">
                    <div class="text-center certification-header">
                        <div class="text-center"><img src="/public/uploads/settings/logo-elearning.png" alt="logo" class="cs-logo" style="width: 140px;"></div>
                        <div class="text-center">
                            <h4>HIKARI ACADEMY 日本語試験</h4>
                            <h4>認定結果及び成績に関する証明書</h4>
                        </div>
                        <div class="text-center">
                            <h4>HIKARI ACADEMY TEST</h4>
                            <h4>CERTIFICATE OF RESULT AND SCORES</h4>
                        </div>
                        <div class="text-center">
                            <h5>HIKARI ACADEMY 株式会社が2021年06月20 日に実施した日本語試験に関し、</h5>
                            <h5>認定結果及び成績を次のとおり証明します。</h5>
                        </div>
                        <div class="text-center">
                            <h5>This is to certify the result and the scores of Hikari Academy - Japanese Test</h5>
                            <h5>given on Jun 20, 2021 administered by Hikari Academy</h5>
                        </div>
                    </div>
                    <table class="table table-bordered" style="width: 100%; margin-top: 60px" id="table-result">
                        <tbody>
                        <tr>
                            <td>氏名&nbsp;Name</td>
                            <td>{{$user->name}}</td>
                        </tr>
                        <tr>
                            <td>生年月日&nbsp;Date of Birth</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>件所&nbsp;Address</td>
                            <td>{{$user->address}}</td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered" style="width: 100%; margin-top: 40px" id="table-result">
                        <tbody>
                        <tr>
                            <td>レべル&nbsp;Level</td>
                            <td id="label-level"></td>
                        </tr>
                        <tr>
                            <td>結果&nbsp;Result</td>
                            <td>合格&nbsp;PASSED</td>
                        </tr>
                        <tr>
                            <td>受験地&nbsp;Test site</td>
                            <td>https://elearning.hikariacademy.edu.vn/</td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered" style="width: 100%; margin-top: 40px; text-align: center;" id="table-ketqua">
                        <tbody>
                            <tr>
                                <td id="td-colspan" colspan="3">得点区分別得点<br>Scores by Scoring Section</td>
                                <td rowspan="2">総合得点 <br/>Total scores</td>
                            </tr>
                            <tr>
                                <td>言語知識（文字・語業・文法）由 <br/> Language Knowledge <br/>(Vocabulary・Grammar)</td>
                                <td id="td-reading">読解 <br/>Reading</td>
                                <td>聴解 <br/>Listening</td>
                            </tr>
                            <tr>
                                <td id="label-vocabulary"></td>
                                <td id="label-reading"></td>
                                <td id="label-listening"></td>
                                <td id="label-total"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-borderless text-end" style="width: 100%; margin-top: 60px" id="">
                        <tbody>
                            <tr>
                                <td style="border-top: none; font-size: 12px">主催者</td>
                            </tr>
                            <tr>
                                <td style="border-top: none; font-size: 12px">Administrator</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-borderless text-end" style="width: 100%; margin-top: 160px" id="">
                        <tbody>
                            <tr>
                                <td style="border-top: none; font-size: 12px">Hikari Academy 株式会社</td>
                            </tr>
                            <tr>
                                <td style="border-top: none; font-size: 12px">Hikari Academy Joint Stock Company</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>