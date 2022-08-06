<div class="contenedor crear">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>
        
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <form action="/crear" method="POST" class="formulario">
            <div class="campo">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="Tu Nombre" value="<?php echo $usuario->nombre; ?>">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Tu E-mail" value="<?php echo $usuario->email; ?>">
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Tu Password" autocomplete="off">
            </div>
            <div class="campo">
                <label for="password2">Repetir Password</label>
                <input type="password" id="password2" name="password2" placeholder="Repite Tu Password" autocomplete="off">
            </div>
            <input type="submit" value="Crear Cuenta" class="boton">
        </form>
        

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
            <a href="/olvide">¿Olvidaste tu password? Haz click aquí</a>
        </div>
    </div> <!--.contenedor-sm-->
</div>