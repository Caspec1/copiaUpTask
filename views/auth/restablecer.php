<div class="contenedor restablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Restablecer contraseña UpTask</p>

        <?php include_once __DIR__ . '/../templates/alertas.php';
        
        if($mostrar) {
        ?>

        <form method="POST" class="formulario">
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Tu Password">
            </div>
            <input type="submit" value="Guardar Password" class="boton">
        </form>

        <?php }?>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
        </div>
    </div> <!--.contenedor-sm-->
</div>