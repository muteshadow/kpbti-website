<!-- Модальне вікно для додавання нового користувача -->
<div class="modal" id="addUserModal">
    <div class="modal-content">
        <h2 class="modal_title">
            Додати нового користувача
            <span class="close"><i class="fa-solid fa-xmark"></i></span>
        </h2>
        <form class="modalForm hide" method="POST" action="admin_add_user.php" id="addUserForm">
            <div class="form-group">
                <input class="input" type="text" name="name" placeholder="Ім'я" required>
            </div>

            <div class="form-group">
                <input class="input" type="text" name="surname" placeholder="Прізвище" required>
            </div>

            <div class="form-group">
                <input class="input" type="text" name="patronymic" placeholder="По батькові">
            </div>

            <div class="form-group">
                <input class="input" type="email" name="email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <input class="input" type="password" name="password" placeholder="Пароль" required>
            </div>

            <label for="role">Роль:</label>
            <select name="role" required>
                <option value="user">Користувач</option>
                <option value="worker">Працівник</option>
                <option value="admin">Адміністратор</option>
            </select><br>

            <div class="modal_btn_container">
                <button class="btn" type="submit">Додати</button>
                <button class="cancel btn">Скасувати</button>
            </div>
        </form>
    </div>
</div>

<!-- Модальне вікно для редагування користувача -->
<div class="modal" id="editUserModal">
    <div class="modal-content">
        <h2 class="modal_title">
            Редагувати користувача
            <span class="close"><i class="fa-solid fa-xmark"></i></span>
        </h2>
        <form class="modalForm hide" method="POST" action="admin_edit_user.php" id="editUserForm">
            <div class="form-group">
                <input class="input" type="hidden" name="user_id" id="editUserId">
            </div>

            <div class="form-group">
                <input class="input" type="text" name="name" id="editName" placeholder="Ім'я" required>
            </div>

            <div class="form-group">
                <input class="input" type="text" name="surname" id="editSurname" placeholder="Прізвище" required>
            </div>

            <div class="form-group">
                <input class="input" type="text" name="patronymic" id="editPatronymic" placeholder="По батькові">
            </div>

            <div class="form-group">
                <input class="input" type="email" name="email" id="editEmail" placeholder="Email" required>
            </div>

            <label for="editRole">Роль:</label>
            <select name="role" id="editRole" required>
                <option value="user">Користувач</option>
                <option value="worker">Працівник</option>
                <option value="admin">Адміністратор</option>
            </select><br>

            <div class="modal_btn_container">
                <button class="btn" type="submit">Зберегти зміни</button>
                <button class="cancel btn">Скасувати</button>
            </div>
        </form>
    </div>
</div>