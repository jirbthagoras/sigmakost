Table "categories" {
  "id" bigint [pk, not null]
  "name" "character varying(100)" [not null, unique]
  "slug" "character varying(100)" [not null, unique]
  "description" text
  "created_at" timestamp(0)
  "updated_at" timestamp(0)
}

Table "kost_category" {
  "id" bigint [pk, not null]
  "kost_id" bigint [not null]
  "category_id" bigint [not null]
  "created_at" timestamp(0)
  "updated_at" timestamp(0)
}

Table "kost_images" {
  "id" bigint [pk, not null]
  "kost_id" bigint [not null]
  "image_path" "character varying(255)" [not null]
  "is_primary" boolean [not null, default: false]
  "order" integer [not null, default: 0]
  "created_at" timestamp(0)
  "updated_at" timestamp(0)
}

Table "kosts" {
  "id" bigint [pk, not null]
  "name" "character varying(255)" [not null]
  "description" text [not null]
  "address" text [not null]
  "contact_number" "character varying(20)" [not null]
  "latitude" decimal(10,7)
  "longitude" decimal(10,7)
  "price_per_month" decimal(12,2) [not null]
  "room_count" integer [not null]
  "available_rooms" integer [not null]
  "facilities" text
  "rules" text
  "status" "character varying(20)" [not null, default: 'active']
  "created_by" bigint [not null]
  "created_at" timestamp(0)
  "updated_at" timestamp(0)
}

Table "payments" {
  "id" bigint [pk, not null]
  "rental_id" bigint [not null]
  "user_id" bigint [not null]
  "amount" decimal(12,2) [not null]
  "due_date" date [not null]
  "paid_date" timestamp(0)
  "payment_method" "character varying(50)"
  "payment_proof" "character varying(255)"
  "status" "character varying(30)" [not null, default: 'unpaid']
  "period_month" integer [not null]
  "period_year" integer [not null]
  "verified_by" bigint
  "verified_at" timestamp(0)
  "notes" text
  "overdue_notification_sent" boolean [not null, default: false]
  "created_at" timestamp(0)
  "updated_at" timestamp(0)
}

Table "rentals" {
  "id" bigint [pk, not null]
  "kost_id" bigint [not null]
  "user_id" bigint [not null]
  "room_number" "character varying(10)"
  "start_date" date [not null]
  "end_date" date
  "duration_months" integer [not null]
  "total_price" decimal(12,2) [not null]
  "status" "character varying(20)" [not null, default: 'pending']
  "approved_by" bigint
  "approved_at" timestamp(0)
  "rejection_reason" text
  "notes" text
  "created_at" timestamp(0)
  "updated_at" timestamp(0)
}

Table "report_responses" {
  "id" bigint [pk, not null]
  "report_id" bigint [not null]
  "admin_id" bigint [not null]
  "message" text [not null]
  "is_internal_note" boolean [not null, default: false]
  "created_at" timestamp(0)
  "updated_at" timestamp(0)
}

Table "reports" {
  "id" bigint [pk, not null]
  "rental_id" bigint [not null]
  "user_id" bigint [not null]
  "kost_id" bigint [not null]
  "title" "character varying(255)" [not null]
  "description" text [not null]
  "category" "character varying(50)" [not null]
  "priority" "character varying(20)" [not null, default: 'medium']
  "status" "character varying(20)" [not null, default: 'pending']
  "images" text
  "created_at" timestamp(0)
  "updated_at" timestamp(0)
}

Table "users" {
  "id" bigint [pk, not null]
  "name" "character varying(255)" [not null]
  "email" "character varying(255)" [unique, not null]
  "email_verified_at" timestamp(0)
  "password" "character varying(255)" [not null]
  "role" "character varying(20)" [not null, default: 'user']
  "phone" "character varying(20)"
  "address" text
  "remember_token" "character varying(100)"
  "created_at" timestamp(0)
  "updated_at" timestamp(0)
}

Ref "kost_category_category_id_foreign":"categories"."id" < "kost_category"."category_id"

Ref "kost_category_kost_id_foreign":"kosts"."id" < "kost_category"."kost_id"

Ref "kost_images_kost_id_foreign":"kosts"."id" < "kost_images"."kost_id"

Ref "kosts_created_by_foreign":"users"."id" < "kosts"."created_by"

Ref "payments_rental_id_foreign":"rentals"."id" < "payments"."rental_id"

Ref "payments_user_id_foreign":"users"."id" < "payments"."user_id"

Ref "payments_verified_by_foreign":"users"."id" < "payments"."verified_by"

Ref "rentals_approved_by_foreign":"users"."id" < "rentals"."approved_by"

Ref "rentals_kost_id_foreign":"kosts"."id" < "rentals"."kost_id"

Ref "rentals_user_id_foreign":"users"."id" < "rentals"."user_id"

Ref "report_responses_admin_id_foreign":"users"."id" < "report_responses"."admin_id"

Ref "report_responses_report_id_foreign":"reports"."id" < "report_responses"."report_id"

Ref "reports_kost_id_foreign":"kosts"."id" < "reports"."kost_id"

Ref "reports_rental_id_foreign":"rentals"."id" < "reports"."rental_id"

Ref "reports_user_id_foreign":"users"."id" < "reports"."user_id"
