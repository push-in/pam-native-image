package dev.pam.pushinbr.pamnativeimage

import android.content.Context
import android.graphics.drawable.ColorDrawable
import android.view.View
import android.widget.ImageView
import coil3.load
import coil3.request.CachePolicy
import dev.pam.nativeapp.protocol.WireMap
import dev.pam.nativeapp.protocol.WireValue
import dev.pam.nativeapp.views.NativeViewFactory
import java.io.File

class CachedImageFactory(private val applicationContext: Context) : NativeViewFactory {
    override fun create(context: Context, emit: (ByteArray) -> Unit): View = CachedImageView(context, emit)
    override fun update(view: View, properties: Map<String, WireValue>) = (view as CachedImageView).update(properties)
    override fun release(view: View) { (view as ImageView).setImageDrawable(null) }

    private inner class CachedImageView(context: Context, private val emit: (ByteArray) -> Unit) : ImageView(context) {
        private var source = ""
        fun update(values: Map<String, WireValue>) {
            scaleType = when (values.integer("contentMode", 1)) { 2L -> ScaleType.CENTER_CROP; 3L -> ScaleType.FIT_XY; 4L -> ScaleType.CENTER; else -> ScaleType.FIT_CENTER }
            val next = values.text("source")
            if (next == source) return
            source = next
            val policy = values.integer("cachePolicy", 1)
            load(resolve(next)) {
                crossfade(values.integer("crossfadeMillis", 120).coerceIn(0, 1000).toInt())
                memoryCachePolicy(if (policy == 3L || policy == 4L) CachePolicy.DISABLED else CachePolicy.ENABLED)
                diskCachePolicy(if (policy == 2L || policy == 4L) CachePolicy.DISABLED else CachePolicy.ENABLED)
                listener(onSuccess = { _, result -> emit(mapOf("event" to WireValue.Integer(1), "width" to WireValue.Integer(result.image.width.toLong()), "height" to WireValue.Integer(result.image.height.toLong()))) }, onError = { _, result -> emit(mapOf("event" to WireValue.Integer(2), "message" to WireValue.Text(result.throwable.message.orEmpty()))) })
            }
        }
        private fun resolve(value: String): Any = if (value.startsWith("https://")) value else sandboxFile(value)
        private fun sandboxFile(path: String): File { val root = applicationContext.filesDir.canonicalFile; val file = File(root, path).canonicalFile; require(file.path.startsWith(root.path + File.separator)); return file }
        private fun emit(values: Map<String, WireValue>) = emit(WireMap.encode(values))
        private fun Map<String, WireValue>.text(key: String) = (get(key) as? WireValue.Text)?.value.orEmpty()
        private fun Map<String, WireValue>.integer(key: String, fallback: Long) = (get(key) as? WireValue.Integer)?.value ?: fallback
    }
}
