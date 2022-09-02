<template>
    <div
        class="py-2 px-2  grid md:grid md:grid-cols-1 2xl:grid-cols-2 xl:grid-cols-2 lg:grid-cols-2"
    >
        <div>
            <label class="text-sm font-semibold text-red-900">
                Business Name :
            </label>
            <span class="text-sm font-semibold">
                {{ business.business_name }}</span
            >
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900">
                Business Identification Number:
            </label>
            <span class="text-sm font-semibold">
                {{ business.business_identification_number }}
            </span>
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900">
                Address :
            </label>
            <span class="text-sm font-semibold"> {{ business.address }}</span>
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900"> Owner: </label>
            <span class="text-sm font-semibold"> {{ business.owner }}</span>
        </div>

        <div>
            <label class="text-sm font-semibold text-red-900">
                Line of Business:
            </label>
            <span class="text-sm font-semibold">
                {{ business.line_of_business }}</span
            >
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900">
                Contact Number:
            </label>
            <span class="text-sm font-semibold">
                {{ business.contact_number }}</span
            >
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900"> Capital: </label>
            <span class="text-sm font-semibold"> {{ business.capital }}</span>
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900">
                Business Tax:
            </label>
            <span class="text-sm font-semibold">
                {{ business.business_tax }}</span
            >
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900"> Code : </label>
            <span class="text-sm font-semibold"> {{ business.code }}</span>
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900">
                Gross Receipts :
            </label>
            <span class="text-sm font-semibold">
                {{ business.gross_receipts }}</span
            >
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900">
                Surcharge :
            </label>
            <span class="text-sm font-semibold"> {{ business.surcharge }}</span>
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900"> Total: </label>
            <span class="text-sm font-semibold"> {{ business.total }}</span>
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900"> O.R No.: </label>
            <span class="text-sm font-semibold">
                {{ business.official_receipt_number }}</span
            >
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900">
                O.R. Date :
            </label>
            <span class="text-sm font-semibold">
                {{ business.official_receipt_date }}</span
            >
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900"> Terms: </label>
            <span class="text-sm font-semibold"> {{ business.terms }}</span>
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900">
                Tax Year:
            </label>
            <span class="text-sm font-semibold"> {{ business.tax_year }}</span>
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900">
                QTR Paid:
            </label>
            <span class="text-sm font-semibold"> {{ business.qtr_paid }}</span>
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900">
                Number of Employees:
            </label>
            <span class="text-sm font-semibold">
                {{ business.number_of_employees }}</span
            >
        </div>
        <div>
            <label class="text-sm font-semibold text-red-900"> Status: </label>
            <span class="text-sm font-semibold"> {{ business.status }}</span>
        </div>
    </div>
</template>

<script>
export default {
    props: ["business"],
};
</script>
