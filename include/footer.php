</main>
</div>

<!-- Footer -->
<footer class="main-footer">
    <div class="footer-content">
        <div class="footer-info">
            <strong>Copyright &copy; <?php echo date('Y'); ?> <span style="color: var(--primary-color);">Art Toy Gallery</span></strong>
            All rights reserved.
        </div>
        <div class="footer-version">
            <b>Version</b> 2.0.0 🎨
        </div>
    </div>
</footer>

<style>
    .main-footer {
        background: white;
        margin: 2rem auto 1rem;
        max-width: 1400px;
        padding: 1.5rem 2rem;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        border-top: 3px solid var(--primary-color);
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--dark-color);
    }

    .footer-info {
        font-size: 0.9rem;
    }

    .footer-version {
        font-size: 0.8rem;
        color: #7f8c8d;
    }

    @media (max-width: 768px) {
        .footer-content {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem;
        }
        
        .main-footer {
            margin: 1rem;
        }
    }
</style>