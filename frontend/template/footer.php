</main>

<footer class="border-top mt-5 py-4">
    <div class="container small d-flex flex-column flex-md-row justify-content-between">
        <div>© <span id="year"></span> VSCR — Proyecto Final ITI-523</div>
        <div>Hecho con Bootstrap 5.3 · <a href="../README.md" class="link-secondary">Docs</a></div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
<script>
    document.getElementById('year').textContent = new Date().getFullYear();
</script>

</body>
</html>