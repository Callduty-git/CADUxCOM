@props(['href' => 'javascript:history.back()'])

<style>
.admin-back-button {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background-color: #6b7280;
    color: white;
    font-size: 12px;
    font-weight: 500;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.15s ease-in-out;
    margin-bottom: 12px;
}

.admin-back-button:hover {
    background-color: #4b5563;
    color: white;
    text-decoration: none;
}

.admin-back-button svg {
    width: 12px;
    height: 12px;
    margin-right: 6px;
}
</style>

<div>
    <a href="{{ $href }}" class="admin-back-button">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path>
        </svg>
        Regresar
    </a>
</div>