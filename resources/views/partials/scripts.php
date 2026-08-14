<script
    src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"
></script>

<script
    src="/assets/vendor/chartjs/chart.umd.js"
></script>

<script
    src="/assets/js/main.js"
></script>

<?php if (!empty($pageScripts) && is_array($pageScripts)): ?>
    <?php foreach ($pageScripts as $script): ?>
        <script
            src="<?= htmlspecialchars(
                (string) $script,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        ></script>
    <?php endforeach; ?>
<?php endif; ?>