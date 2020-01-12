import { Component, ViewChild, VERSION, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from "@angular/forms";
import { ContactService } from './contact.service';
import { HttpParams } from "@angular/common/http";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {

  myForm: FormGroup;
  message : String;
  
  constructor(public fb: FormBuilder, public contactService: ContactService) { }

  ngOnInit(): void {
    this.reactiveForm();   
  }

  resolved(captchaResponse: string) {
    //console.log(`Resolved captcha with response: ${captchaResponse}`);
  }

  /* Reactive form */
  reactiveForm() {
    this.myForm = this.fb.group({
      name: ['', [Validators.required]],
      email: ['', [Validators.required, Validators.email]],
      subject: ['', [Validators.required]],
      message: ['', [Validators.required]],
      recaptcha: ['', Validators.required]
    })
  }

  /* Handle form errors in Angular 8 */
  public errorHandling = (control: string, error: string) => {
    return this.myForm.controls[control].hasError(error);
  }

  submitForm() {

    const payload = new HttpParams()
      .set('name', this.myForm.value.name)
      .set('email', this.myForm.value.email)
      .set('subject', this.myForm.value.subject)
      .set('message', this.myForm.value.message);

    this.contactService.sendMessage(payload).subscribe(resp => {
      this.myForm.reset();
      this.myForm.markAsUntouched();
      Object.keys(this.myForm.controls).forEach((name) => {
        let control = this.myForm.controls[name];
        control.reset();
        control.setErrors(null);
      });
      //this.myForm.fadeOut();
      this.message = "Su mensaje ha sido enviado exitosamente, pronto nos prondremos en contacto con ud, feliz día !";
      setTimeout("this.message='';", 3000);
    },
      (err) => console.log(err)
    );

    return true;
  }
}
