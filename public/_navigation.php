<script type="text/javascript" src="/js/navigation.js?<?= md5(microtime()) ?>"></script>
<div>
    <span>Welcome <?= $currentUser->username ?></span><br/>
    <span><button id="btn-members" data-dest="/members.php">Members</button></span>
    <span><button id="btn-admin" data-dest="/admin.php">Admin</button></span>
    <span><button id="btn-logout">Logout</button></span>
</div>
<br/>