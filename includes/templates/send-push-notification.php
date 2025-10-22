<?php ?>
<div class="epasscard-push-notification epasscard-hidden">
    <p>You can use the following placeholders to personalise your push notification: []</p>

    <div class="epasscard-field-group">
        <label>Push notification content <span>*</span></label>
        <textarea class="field-value" rows="4" cols="100%"
            placeholder="Write your push notification content here..."></textarea>

        <div class="push-notification-inner">
            <div>
                <label>Publication date</label>
                <input type="date">
            </div>
            <div>
                <label>Time zone <span>*</span></label>
                <select>
                    <option value="bangladesh">Bangladesh</option>
                    <option value="canada">Canada</option>
                    <option value="australia">Australia</option>
                    <option value="usa">USA</option>
                </select>
            </div>
        </div>
        <button class="epasscard-primary-btn">Send Notification</button>
        <button class="single-pass-back-btn epasscard-primary-btn">Back</button>
    </div>