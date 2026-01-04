/**
 * VetCare WhatsApp Notification System
 * JavaScript client for sending WhatsApp notifications
 */

class WhatsAppNotifier {
    constructor() {
        this.apiUrl = '/api/notifications';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    /**
     * Send appointment reminder
     */
    async sendAppointmentReminder(appointmentId) {
        try {
            const response = await fetch(`${this.apiUrl}/appointment/${appointmentId}/reminder`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            
            if (result.success) {
                // If there's a WhatsApp link, open it
                if (result.whatsapp_link) {
                    const popup = window.open(result.whatsapp_link, '_blank');
                    
                    // If popup blocked, copy to clipboard as fallback
                    if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                        this.copyToClipboard(result.whatsapp_link);
                        this.showNotification('warning', '🚫 Popup blocked! WhatsApp link copied to clipboard. Paste in browser to open.');
                    } else {
                        this.showNotification('success', 'Opening WhatsApp... 📱');
                    }
                } else {
                    this.showNotification('success', 'WhatsApp notification sent successfully! ✅');
                }
            } else {
                this.showNotification('error', result.message || 'Failed to send notification');
            }

            return result;
        } catch (error) {
            console.error('Error sending reminder:', error);
            this.showNotification('error', 'Network error occurred');
            return { success: false, error: error.message };
        }
    }

    /**
     * Send appointment confirmation
     */
    async sendAppointmentConfirmation(appointmentId) {
        try {
            const response = await fetch(`${this.apiUrl}/appointment/${appointmentId}/confirmation`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            
            if (result.success) {
                // If there's a WhatsApp link, open it
                if (result.whatsapp_link) {
                    const popup = window.open(result.whatsapp_link, '_blank');
                    
                    // If popup blocked, copy to clipboard as fallback
                    if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                        this.copyToClipboard(result.whatsapp_link);
                        this.showNotification('warning', '🚫 Popup blocked! WhatsApp link copied to clipboard. Paste in browser to open.');
                    } else {
                        this.showNotification('success', 'Opening WhatsApp... 📱');
                    }
                } else {
                    this.showNotification('success', 'Appointment confirmation sent via WhatsApp! 📱');
                }
            } else {
                this.showNotification('error', result.message || 'Failed to send confirmation');
            }

            return result;
        } catch (error) {
            console.error('Error sending confirmation:', error);
            this.showNotification('error', 'Network error occurred');
            return { success: false, error: error.message };
        }
    }

    /**
     * Send prescription notification
     */
    async sendPrescriptionNotification(prescriptionId) {
        try {
            const response = await fetch(`${this.apiUrl}/prescription/${prescriptionId}/notification`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            
            if (result.success) {
                // If there's a WhatsApp link, open it
                if (result.whatsapp_link) {
                    const popup = window.open(result.whatsapp_link, '_blank');
                    
                    // If popup blocked, copy to clipboard as fallback
                    if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                        this.copyToClipboard(result.whatsapp_link);
                        this.showNotification('warning', '🚫 Popup blocked! WhatsApp link copied to clipboard. Paste in browser to open.');
                    } else {
                        this.showNotification('success', 'Opening WhatsApp... 📱');
                    }
                } else {
                    this.showNotification('success', 'Prescription notification sent! 💊');
                }
            } else {
                this.showNotification('error', result.message || 'Failed to send notification');
            }

            return result;
        } catch (error) {
            console.error('Error sending prescription notification:', error);
            this.showNotification('error', 'Network error occurred');
            return { success: false, error: error.message };
        }
    }

    /**
     * Get WhatsApp link for manual sending
     */
    async getWhatsAppLink(phone, message) {
        try {
            const response = await fetch(`${this.apiUrl}/whatsapp-link`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone, message })
            });

            const result = await response.json();
            
            if (result.success && result.link) {
                // Open WhatsApp in new window
                const popup = window.open(result.link, '_blank');
                
                // If popup blocked, copy to clipboard as fallback
                if (!popup || popup.closed || typeof popup.closed === 'undefined') {
                    this.copyToClipboard(result.link);
                    this.showNotification('warning', '🚫 Popup blocked! Link copied to clipboard.');
                } else {
                    this.showNotification('success', 'Opening WhatsApp...');
                }
            }

            return result;
        } catch (error) {
            console.error('Error getting WhatsApp link:', error);
            this.showNotification('error', 'Failed to generate WhatsApp link');
            return { success: false, error: error.message };
        }
    }

    /**
     * Send custom notification
     */
    async sendCustomNotification(phone, message) {
        try {
            const response = await fetch(`${this.apiUrl}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone, message })
            });

            const result = await response.json();
            
            if (result.success) {
                this.showNotification('success', 'Notification sent successfully!');
            } else {
                this.showNotification('error', result.message || 'Failed to send notification');
            }

            return result;
        } catch (error) {
            console.error('Error sending notification:', error);
            this.showNotification('error', 'Network error occurred');
            return { success: false, error: error.message };
        }
    }

    /**
     * Copy text to clipboard
     */
    copyToClipboard(text) {
        // Modern Clipboard API (preferred)
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).catch(err => {
                console.error('Failed to copy to clipboard:', err);
                this.fallbackCopyToClipboard(text);
            });
        } else {
            // Fallback for older browsers
            this.fallbackCopyToClipboard(text);
        }
    }

    /**
     * Fallback clipboard copy for older browsers
     */
    fallbackCopyToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.top = '-9999px';
        textArea.style.left = '-9999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
        } catch (err) {
            console.error('Fallback copy failed:', err);
        }
        
        document.body.removeChild(textArea);
    }

    /**
     * Show notification to user
     */
    showNotification(type, message) {
        // Create notification element
        const notification = document.createElement('div');
        
        // Color based on type
        let bgColor = 'bg-red-500';
        let icon = 'exclamation-circle';
        
        if (type === 'success') {
            bgColor = 'bg-green-500';
            icon = 'check-circle';
        } else if (type === 'warning') {
            bgColor = 'bg-yellow-500';
            icon = 'exclamation-triangle';
        }
        
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ${bgColor} text-white`;
        notification.innerHTML = `
            <div class="flex items-center space-x-3">
                <i class="fas fa-${icon}"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto-remove after 5 seconds for warnings, 3 seconds for others
        const timeout = type === 'warning' ? 5000 : 3000;
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, timeout);
    }

    /**
     * Initialize notification buttons
     */
    initButtons() {
        // Add event listeners to all WhatsApp notification buttons
        document.querySelectorAll('[data-whatsapp-reminder]').forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const appointmentId = button.dataset.whatsappReminder;
                
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
                
                await this.sendAppointmentReminder(appointmentId);
                
                button.disabled = false;
                button.innerHTML = '<i class="fab fa-whatsapp mr-2"></i>Send Reminder';
            });
        });

        document.querySelectorAll('[data-whatsapp-confirmation]').forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const appointmentId = button.dataset.whatsappConfirmation;
                
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
                
                await this.sendAppointmentConfirmation(appointmentId);
                
                button.disabled = false;
                button.innerHTML = '<i class="fab fa-whatsapp mr-2"></i>Send Confirmation';
            });
        });

        document.querySelectorAll('[data-whatsapp-prescription]').forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const prescriptionId = button.dataset.whatsappPrescription;
                
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
                
                await this.sendPrescriptionNotification(prescriptionId);
                
                button.disabled = false;
                button.innerHTML = '<i class="fab fa-whatsapp mr-2"></i>Notify Customer';
            });
        });
    }
}

// Initialize WhatsApp notifier globally
window.whatsappNotifier = new WhatsAppNotifier();

// Auto-initialize buttons when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.whatsappNotifier.initButtons();
});
