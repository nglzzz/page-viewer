<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>View Page</h1>
        </div>
        <div class="col-md-8">
            <form method="get">
                <div class="form-group">
                    <label for="pageName">Page name</label>
                    <input type="text" class="form-control" id="pageName" name="name" placeholder="Enter page name" value="<?= $name ?? '' ?>">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $text ?? '' ?>
        </div>
    </div>
</div>
