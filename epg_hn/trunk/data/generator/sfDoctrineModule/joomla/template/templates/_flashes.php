[?php if ($sf_user->hasFlash('notice')): ?]
<dl id="system-message">
    <dt class="notice">Message</dt>
    <dd class="notice fade">
        <ul>
            <li>[?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?]</li>
        </ul>
    </dd>
</dl>
[?php endif; ?]

[?php if ($sf_user->hasFlash('error')): ?]
<dl id="system-message">
    <dt class="error">Message</dt>
    <dd class="error fade">
        <ul>
            <li>[?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?]</li>
        </ul>
    </dd>
</dl>
[?php endif; ?]
