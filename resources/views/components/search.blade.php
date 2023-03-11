<form class="ml-auto" action="?">
    <div class="input-group">
        <input
        type="text"
        name="search"
        class="form-control"
        placeholder="Search..."
        value="<?= request()->search ?>">
        <div class="input-group-append">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</form>