<style>
    .card-body h5 {
        margin-bottom: 0;
    }
</style>
<div class="container">
    <div class="card">
        <div class="card-header">
            Account Credit
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="card text-white bg-primary">              
                        <div class="card-body">
                            <div class="row">
                                <div class="col"><h5>{$remainingCredit}</h5></div>
                                <div class="col text-right">Remaining Credit</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-success">              
                        <div class="card-body">
                            <div class="row">
                                <div class="col"><h5>{$totalCredit}</h5></div>
                                <div class="col text-right">Total Credit</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-danger">              
                        <div class="card-body">
                            <div class="row">
                                <div class="col"><h5>{$usedCredit}</h5></div>
                                <div class="col text-right">Used Credit</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <h5 class="mb-3 mt-5">Credit History</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $credits as $credit}
                        <tr>
                            <td>{$credit->date|date_format:"%d/%m/%Y"}</td>
                            <td>{$credit->description}</td>
                            <td>{$currency}{$credit->amount}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>

            <div class="mt-5">
                {$links}
            </div>
            
        </div>
    </div>
</div>
